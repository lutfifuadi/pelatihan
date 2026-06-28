<?php

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Daftar pelatihan lengkap dengan progress dan status untuk dashboard admin.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Pelatihan>
     */
    public function getPelatihanList(): Collection
    {
        $now = now();

        $pelatihanList = Pelatihan::select([
            'id',
            'nama',
            'batch',
            'deskripsi',
            'kuota',
            'is_active',
            'tanggal_mulai',
            'tanggal_selesai',
            'dinas_id',
            'created_at',
        ])
            ->with('dinas:id,nama_dinas,singkatan')
            ->withCount([
                'enrollments',
                'enrollments as pending_count' => function ($query) {
                    $query->where('status', EnrollmentStatus::Pending);
                },
                'enrollments as approved_count' => function ($query) {
                    $query->where('status', EnrollmentStatus::Approved);
                },
                'enrollments as confirmed_count' => function ($query) {
                    $query->where('status', EnrollmentStatus::Confirmed);
                },
                'schedules',
                'schedules as schedules_done_count' => function ($query) use ($now) {
                    $query->where('tanggal', '<', $now->toDateString());
                },
            ])
            ->orderBy('is_active', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Map additional computed fields
        $pelatihanList->transform(function ($pelatihan) use ($now) {
            // Progress pendaftar: confirmed / kuota * 100
            $kuota = $pelatihan->kuota ?? 0;
            $pelatihan->progress_pendaftar = $kuota > 0
                ? round(($pelatihan->confirmed_count / $kuota) * 100)
                : 0;

            // Progress waktu: elapsed / total * 100 (dibatasi 0-100)
            $tanggalMulai = $pelatihan->tanggal_mulai;
            $tanggalSelesai = $pelatihan->tanggal_selesai;

            if ($tanggalMulai && $tanggalSelesai) {
                $totalDays = (int) $tanggalMulai->diffInDays($tanggalSelesai) ?: 1;
                $elapsedDays = (int) $tanggalMulai->diffInDays($now, false);

                $progressWaktu = ($elapsedDays / $totalDays) * 100;
                $pelatihan->progress_waktu = (int) round(max(0, min(100, $progressWaktu)));
            } else {
                $pelatihan->progress_waktu = 0;
            }

            // Status label & color
            if (!$pelatihan->is_active) {
                $pelatihan->status_label = 'Nonaktif';
                $pelatihan->status_color = 'secondary';
            } elseif ($tanggalSelesai && $now->greaterThan($tanggalSelesai)) {
                $pelatihan->status_label = 'Selesai';
                $pelatihan->status_color = 'info';
            } elseif ($tanggalMulai && $now->lessThan($tanggalMulai)) {
                $pelatihan->status_label = 'Akan Datang';
                $pelatihan->status_color = 'warning';
            } else {
                $pelatihan->status_label = 'Aktif';
                $pelatihan->status_color = 'success';
            }

            // Sisa hari (bisa negatif jika sudah lewat)
            $pelatihan->sisa_hari = $tanggalSelesai
                ? (int) $now->diffInDays($tanggalSelesai, false)
                : null;

            return $pelatihan;
        });

        return $pelatihanList;
    }

    /**
     * Trend pendaftaran per hari untuk N hari terakhir.
     *
     * @param  int  $days  Jumlah hari ke belakang (default 7)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRegistrationTrend(int $days = 7): Collection
    {
        $since = now()->subDays($days)->startOfDay();

        $trend = Enrollment::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $since)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        return $trend;
    }

    /**
     * Top instruktur berdasarkan jumlah sesi jadwal (schedulesAsInstruktur) yang diampu
     * pada pelatihan yang masih aktif.
     *
     * @param  int  $limit  Jumlah top instruktur (default 5)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopInstruktur(int $limit = 5): Collection
    {
        return User::where('role', 'instruktur')
            ->withCount(['schedulesAsInstruktur as total_sessions' => function ($q) {
                $q->whereHas('pelatihan', fn($q) => $q->where('is_active', true));
            }])
            ->orderBy('total_sessions', 'desc')
            ->take($limit)
            ->get();
    }
}
