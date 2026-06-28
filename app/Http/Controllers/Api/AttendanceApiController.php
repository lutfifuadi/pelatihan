<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Pelatihan;
use App\Models\Schedule;
use App\Models\AuditLog;
use App\Enums\EnrollmentStatus;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AttendanceApiController extends Controller
{
    /**
     * Generate a short-lived QR token for the confirmed participant.
     */
    public function generatePesertaToken(Request $request, $pelatihanId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('pelatihan_id', $pelatihanId)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'message' => 'Pendaftaran tidak ditemukan untuk pelatihan ini.'
            ], 404);
        }

        if ($enrollment->status !== EnrollmentStatus::Confirmed) {
            return response()->json([
                'message' => 'Status pendaftaran harus Confirmed untuk melakukan presensi.'
            ], 403);
        }

        $now = now()->timestamp;
        $expireAt = $now + 20; // 20 seconds from now

        $payload = [
            'enrollment_id' => $enrollment->id,
            'timestamp_generated' => $now,
            'expire_at' => $expireAt,
        ];

        $encryptedPayload = Crypt::encryptString(json_encode($payload));
        $signature = hash_hmac('sha256', $encryptedPayload, config('app.key'));

        $qrToken = base64_encode(json_encode([
            'payload' => $encryptedPayload,
            'signature' => $signature,
        ]));

        return response()->json([
            'qr_token' => $qrToken,
            'expires_in' => 20,
            'expire_at' => $expireAt,
        ]);
    }

    /**
     * Verify participant's QR code scan by Panitia / Admin.
     */
    public function panitiaCheckIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_token' => 'required|string',
            'scan_timestamp' => 'required|integer',
            'latitude_panitia' => 'required|numeric',
            'longitude_panitia' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $qrToken = $request->input('qr_token');
        $scanTimestamp = (int) $request->input('scan_timestamp');
        $latPanitia = $request->input('latitude_panitia');
        $lonPanitia = $request->input('longitude_panitia');

        // 1. Replay Attack Cache Check
        $tokenHash = 'qr_attendance_' . md5($qrToken);
        if (Cache::has($tokenHash)) {
            return response()->json([
                'message' => 'Token QR telah digunakan (Replay Attack terdeteksi).'
            ], 422);
        }

        // 2. Decode & Verifikasi Signature Token
        $decodedToken = json_decode(base64_decode($qrToken), true);
        if (!$decodedToken || !isset($decodedToken['payload']) || !isset($decodedToken['signature'])) {
            return response()->json([
                'message' => 'Format token QR tidak valid.'
            ], 422);
        }

        $encryptedPayload = $decodedToken['payload'];
        $signature = $decodedToken['signature'];

        $expectedSignature = hash_hmac('sha256', $encryptedPayload, config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json([
                'message' => 'Signature token tidak valid.'
            ], 422);
        }

        // 3. Dekripsi Payload
        try {
            $payloadJson = Crypt::decryptString($encryptedPayload);
            $payload = json_decode($payloadJson, true);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendekripsi token.'
            ], 422);
        }

        if (!$payload || !isset($payload['enrollment_id']) || !isset($payload['timestamp_generated']) || !isset($payload['expire_at'])) {
            return response()->json([
                'message' => 'Payload token tidak lengkap.'
            ], 422);
        }

        $enrollmentId = $payload['enrollment_id'];
        $timestampGenerated = (int) $payload['timestamp_generated'];

        // 4. Validasi Waktu Token (Maksimal 20 Detik)
        $timeDifference = abs($scanTimestamp - $timestampGenerated);
        if ($timeDifference > 20) {
            return response()->json([
                'message' => 'Token QR telah kadaluarsa (melebihi batas waktu 20 detik).'
            ], 422);
        }

        // 5. Validasi Sync Offline (Maksimal 2 jam dari waktu server)
        $serverTime = now()->timestamp;
        $syncDifference = abs($serverTime - $scanTimestamp);
        if ($syncDifference > 7200) {
            return response()->json([
                'message' => 'Waktu pada perangkat tidak sinkron dengan server.'
            ], 422);
        }

        // Ambil Data Enrollment & Pelatihan
        $enrollment = Enrollment::with('pelatihan', 'user')->find($enrollmentId);
        if (!$enrollment) {
            return response()->json([
                'message' => 'Data pendaftaran tidak ditemukan.'
            ], 422);
        }

        if ($enrollment->status !== EnrollmentStatus::Confirmed) {
            return response()->json([
                'message' => 'Status pendaftaran harus Confirmed.'
            ], 422);
        }

        $pelatihan = $enrollment->pelatihan;
        if (!$pelatihan) {
            return response()->json([
                'message' => 'Data pelatihan tidak ditemukan.'
            ], 422);
        }

        // 6. Validasi Geofencing
        $distance = $this->haversineDistance(
            $latPanitia,
            $lonPanitia,
            $pelatihan->latitude,
            $pelatihan->longitude
        );

        $radiusToleransi = $pelatihan->radius_toleransi ?? 50;
        if ($distance > $radiusToleransi) {
            return response()->json([
                'message' => 'Posisi panitia berada di luar radius lokasi pelatihan (' . round($distance) . ' meter).'
            ], 422);
        }

        // Tentukan Pertemuan Ke
        $schedule = Schedule::where('pelatihan_id', $pelatihan->id)
            ->where('tanggal', Carbon::today())
            ->first();

        if ($schedule) {
            $pertemuanKe = $schedule->pertemuan_ke;
        } else {
            $attendedPertemuanIds = Attendance::where('enrollment_id', $enrollment->id)->pluck('pertemuan_ke')->toArray();
            $nextSchedule = Schedule::where('pelatihan_id', $pelatihan->id)
                ->whereNotIn('pertemuan_ke', $attendedPertemuanIds)
                ->orderBy('pertemuan_ke')
                ->first();
            $pertemuanKe = $nextSchedule ? $nextSchedule->pertemuan_ke : (count($attendedPertemuanIds) + 1);
        }

        // 7. Cek Duplikasi Kehadiran
        $hasAttendanceToday = Attendance::where('enrollment_id', $enrollment->id)
            ->where('date', Carbon::today())
            ->exists();

        if ($hasAttendanceToday) {
            return response()->json([
                'message' => 'Peserta sudah melakukan presensi hari ini.'
            ], 422);
        }

        $hasAttendancePertemuan = Attendance::where('enrollment_id', $enrollment->id)
            ->where('pertemuan_ke', $pertemuanKe)
            ->exists();

        if ($hasAttendancePertemuan) {
            return response()->json([
                'message' => 'Presensi untuk pertemuan ke-' . $pertemuanKe . ' sudah tercatat.'
            ], 422);
        }

        // Simpan hash token ke cache untuk 20 detik (Replay Attack Prevention)
        Cache::put($tokenHash, true, 20);

        // 8. Catat Kehadiran
        $attendance = Attendance::create([
            'enrollment_id' => $enrollment->id,
            'pertemuan_ke' => $pertemuanKe,
            'status' => 'hadir',
            'date' => Carbon::today(),
            'verified_method' => 'QR',
            'latitude_panitia' => $latPanitia,
            'longitude_panitia' => $lonPanitia,
            'distance_from_center' => (int) round($distance),
            'ip_address' => $request->ip(),
            'device_user' => $request->userAgent(),
            'scanner_by' => Auth::id(),
        ]);

        // 9. Buat Audit Log
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $actorRole = in_array($currentUser->role, ['admin', 'panitia', 'instruktur']) ? $currentUser->role : 'panitia';
        AuditLog::create([
            'actor_id' => Auth::id(),
            'actor_role' => $actorRole,
            'action_type' => 'create',
            'target_entity' => 'attendance',
            'target_id' => $attendance->id,
            'description' => "Berhasil memverifikasi kehadiran QR untuk peserta {$enrollment->user->name} pada pertemuan ke-{$pertemuanKe}.",
            'new_data' => $attendance->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Kehadiran berhasil dicatat.',
            'attendance' => $attendance
        ]);
    }

    /**
     * Bypass attendance for a participant manually by Panitia / Admin.
     */
    public function panitiaBypass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enrollment_id' => 'required|integer|exists:enrollments,id',
            'bypass_reason' => 'required|string|max:255',
            'latitude_panitia' => 'required|numeric',
            'longitude_panitia' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollmentId = $request->input('enrollment_id');
        $bypassReason = $request->input('bypass_reason');
        $latPanitia = $request->input('latitude_panitia');
        $lonPanitia = $request->input('longitude_panitia');

        $enrollment = Enrollment::with('pelatihan', 'user')->find($enrollmentId);
        if (!$enrollment) {
            return response()->json([
                'message' => 'Data pendaftaran tidak ditemukan.'
            ], 422);
        }

        // Validasi Status Confirmed
        if ($enrollment->status !== EnrollmentStatus::Confirmed) {
            return response()->json([
                'message' => 'Status pendaftaran harus Confirmed untuk melakukan bypass.'
            ], 422);
        }

        $pelatihan = $enrollment->pelatihan;
        if (!$pelatihan) {
            return response()->json([
                'message' => 'Data pelatihan tidak ditemukan.'
            ], 422);
        }

        // Validasi Geofencing
        $distance = $this->haversineDistance(
            $latPanitia,
            $lonPanitia,
            $pelatihan->latitude,
            $pelatihan->longitude
        );

        $radiusToleransi = $pelatihan->radius_toleransi ?? 50;
        if ($distance > $radiusToleransi) {
            return response()->json([
                'message' => 'Posisi panitia berada di luar radius lokasi pelatihan (' . round($distance) . ' meter).'
            ], 422);
        }

        // Tentukan Pertemuan Ke
        $schedule = Schedule::where('pelatihan_id', $pelatihan->id)
            ->where('tanggal', Carbon::today())
            ->first();

        if ($schedule) {
            $pertemuanKe = $schedule->pertemuan_ke;
        } else {
            $attendedPertemuanIds = Attendance::where('enrollment_id', $enrollment->id)->pluck('pertemuan_ke')->toArray();
            $nextSchedule = Schedule::where('pelatihan_id', $pelatihan->id)
                ->whereNotIn('pertemuan_ke', $attendedPertemuanIds)
                ->orderBy('pertemuan_ke')
                ->first();
            $pertemuanKe = $nextSchedule ? $nextSchedule->pertemuan_ke : (count($attendedPertemuanIds) + 1);
        }

        // Cek Duplikasi Kehadiran
        $hasAttendanceToday = Attendance::where('enrollment_id', $enrollment->id)
            ->where('date', Carbon::today())
            ->exists();

        if ($hasAttendanceToday) {
            return response()->json([
                'message' => 'Peserta sudah melakukan presensi hari ini.'
            ], 422);
        }

        $hasAttendancePertemuan = Attendance::where('enrollment_id', $enrollment->id)
            ->where('pertemuan_ke', $pertemuanKe)
            ->exists();

        if ($hasAttendancePertemuan) {
            return response()->json([
                'message' => 'Presensi untuk pertemuan ke-' . $pertemuanKe . ' sudah tercatat.'
            ], 422);
        }

        // Catat Kehadiran Manual (Bypass)
        $attendance = Attendance::create([
            'enrollment_id' => $enrollment->id,
            'pertemuan_ke' => $pertemuanKe,
            'status' => 'hadir',
            'date' => Carbon::today(),
            'verified_method' => 'Manual',
            'latitude_panitia' => $latPanitia,
            'longitude_panitia' => $lonPanitia,
            'distance_from_center' => (int) round($distance),
            'ip_address' => $request->ip(),
            'device_user' => $request->userAgent(),
            'bypassed_by' => Auth::id(),
            'bypass_reason' => $bypassReason,
        ]);

        // Buat Audit Log
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $actorRole = in_array($currentUser->role, ['admin', 'panitia', 'instruktur']) ? $currentUser->role : 'panitia';
        AuditLog::create([
            'actor_id' => Auth::id(),
            'actor_role' => $actorRole,
            'action_type' => 'bypass',
            'target_entity' => 'attendance',
            'target_id' => $attendance->id,
            'description' => "Bypass kehadiran manual untuk peserta {$enrollment->user->name} pada pertemuan ke-{$pertemuanKe} dengan alasan: {$bypassReason}.",
            'new_data' => $attendance->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Kehadiran berhasil dicatat (Bypass manual).',
            'attendance' => $attendance
        ]);
    }

    /**
     * Get realtime attendance stats and participants list for Admin / Instruktur.
     */
    public function getRealtimeAttendance($pelatihanId)
    {
        $pelatihan = Pelatihan::find($pelatihanId);
        if (!$pelatihan) {
            return response()->json([
                'message' => 'Data pelatihan tidak ditemukan.'
            ], 404);
        }

        $totalConfirmed = Enrollment::where('pelatihan_id', $pelatihanId)
            ->where('status', EnrollmentStatus::Confirmed)
            ->count();

        $totalHadir = Attendance::whereHas('enrollment', function ($query) use ($pelatihanId) {
                $query->where('pelatihan_id', $pelatihanId);
            })
            ->where('status', 'hadir')
            ->where('date', Carbon::today())
            ->count();

        $totalBelumHadir = max(0, $totalConfirmed - $totalHadir);

        $hadirList = Attendance::with(['enrollment.user'])
            ->whereHas('enrollment', function ($query) use ($pelatihanId) {
                $query->where('pelatihan_id', $pelatihanId);
            })
            ->where('status', 'hadir')
            ->where('date', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                $user = $attendance->enrollment->user;
                return [
                    'id' => $attendance->id,
                    'enrollment_id' => $attendance->enrollment_id,
                    'name' => $user->name ?? '-',
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : ($user->profile_photo_url ?? null),
                    'checked_in_at' => $attendance->created_at->toIso8601String(),
                    'verified_method' => $attendance->verified_method,
                ];
            });

        return response()->json([
            'total_confirmed_participants' => $totalConfirmed,
            'total_hadir_today' => $totalHadir,
            'total_belum_hadir_today' => $totalBelumHadir,
            'participants_hadir' => $hadirList
        ]);
    }

    /**
     * Search confirmed participants in a training by name or NIK.
     */
    public function searchPeserta(Request $request, $pelatihanId)
    {
        $q = $request->input('q');

        $enrollments = Enrollment::with('user')
            ->where('pelatihan_id', $pelatihanId)
            ->where('status', EnrollmentStatus::Confirmed)
            ->whereHas('user', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('name', 'like', "%{$q}%")
                             ->orWhere('nik', 'like', "%{$q}%");
                });
            })
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'enrollment_id' => $enrollment->id,
                    'user_id' => $enrollment->user_id,
                    'name' => $enrollment->user->name ?? '-',
                    'nik' => $enrollment->user->nik ?? '-',
                    'status' => $enrollment->statusLabel(),
                ];
            });

        return response()->json($enrollments);
    }

    /**
     * Calculate distance using Haversine formula (in meters).
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $latFrom = deg2rad((double) $lat1);
        $lonFrom = deg2rad((double) $lon1);
        $latTo = deg2rad((double) $lat2);
        $lonTo = deg2rad((double) $lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
