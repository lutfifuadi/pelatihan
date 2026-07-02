<?php

namespace App\Http\Controllers;

use App\Models\PengumumanPelatihan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PengumumanPelatihanController extends Controller
{
    /**
     * Mengambil daftar pengumuman pelatihan untuk publik/siswa.
     * Hanya mengembalikan pengumuman publik atau pengumuman privat dari pelatihan yang sedang diikutinya.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Jika user tidak login, hanya tampilkan pengumuman publik
        if (!$user) {
            $pengumuman = PengumumanPelatihan::with('pelatihan')
                ->where('is_private', false)
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Daftar pengumuman pelatihan publik berhasil diambil.',
                'data' => $pengumuman
            ]);
        }

        // Jika admin, tampilkan semuanya
        if ($user->role === 'admin' || $user->is_admin) {
            $pengumuman = PengumumanPelatihan::with('pelatihan')
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Daftar semua pengumuman pelatihan berhasil diambil (Admin).',
                'data' => $pengumuman
            ]);
        }

        // Jika siswa login:
        // Tampilkan pengumuman yang bersifat publik, ATAU pengumuman privat dari pelatihan di mana siswa terdaftar.
        // Kita gunakan relasi pivot (seperti di policy) untuk memfilter pelatihan yang diikutinya.
        $pengumuman = PengumumanPelatihan::with('pelatihan')
            ->where(function ($query) use ($user) {
                $query->where('is_private', false)
                    ->orWhere(function ($subQuery) use ($user) {
                        $subQuery->where('is_private', true)
                            ->whereHas('pelatihan', function ($pelatihanQuery) use ($user) {
                                // Cek relasi default 'siswa' atau 'users' jika ada
                                $pelatihanQuery->where(function ($q) use ($user) {
                                    $q->whereHas('siswa', function ($sq) use ($user) {
                                        $sq->where('users.id', $user->id)
                                          ->orWhere('user_id', $user->id)
                                          ->orWhere('siswa_id', $user->id);
                                    })
                                    ->orWhereHas('users', function ($sq) use ($user) {
                                        $sq->where('users.id', $user->id);
                                    })
                                    // Fallback pengecekan via manual exist query atau join jika relasi query builder gagal
                                    ->orWhereExists(function ($existsQuery) use ($user) {
                                        $existsQuery->select(DB::raw(1))
                                            ->from('pelatihan_user')
                                            ->whereColumn('pelatihan_user.pelatihan_id', 'pelatihans.id')
                                            ->where('pelatihan_user.user_id', $user->id);
                                    })
                                    ->orWhereExists(function ($existsQuery) use ($user) {
                                        $existsQuery->select(DB::raw(1))
                                            ->from('pendaftaran_pelatihan')
                                            ->whereColumn('pendaftaran_pelatihan.pelatihan_id', 'pelatihans.id')
                                            ->where('pendaftaran_pelatihan.user_id', $user->id);
                                    })
                                    ->orWhereExists(function ($existsQuery) use ($user) {
                                        $existsQuery->select(DB::raw(1))
                                            ->from('enrollments')
                                            ->whereColumn('enrollments.pelatihan_id', 'pelatihans.id')
                                            ->where('enrollments.user_id', $user->id)
                                            ->where('enrollments.status', 'approved');
                                    });
                                });
                            });
                    });
            })
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengumuman pelatihan yang dapat diakses berhasil diambil.',
            'data' => $pengumuman
        ]);
    }

    /**
     * Mengambil detail pengumuman pelatihan.
     * Menerapkan policy view untuk verifikasi akses.
     */
    public function show(PengumumanPelatihan $pengumuman): JsonResponse
    {
        Gate::authorize('view', $pengumuman);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengumuman pelatihan berhasil diambil.',
            'data' => $pengumuman->load('pelatihan')
        ]);
    }

    /**
     * Mengambil pengumuman privat yang aktif (is_private = true) untuk pelatihan tersebut.
     * Memverifikasi hak akses/terdaftar menggunakan logic policy.
     */
    public function getPrivateAnnouncements(\App\Models\Pelatihan $pelatihan): JsonResponse
    {
        // Untuk memverifikasi apakah user terdaftar, kita bisa menggunakan policy.
        // Karena Policy `view` menerima object PengumumanPelatihan, mari buat instance dummy dengan pelatihan_id tersebut.
        $dummyAnnouncement = new PengumumanPelatihan();
        $dummyAnnouncement->pelatihan_id = $pelatihan->id;
        $dummyAnnouncement->is_private = true;

        Gate::authorize('view', $dummyAnnouncement);

        $pengumuman = PengumumanPelatihan::where('pelatihan_id', $pelatihan->id)
            ->where('is_private', true)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengumuman privat berhasil diambil.',
            'data' => $pengumuman
        ]);
    }

    /**
     * Mengambil pengumuman publik (is_private = false) untuk pelatihan tersebut.
     */
    public function getPublicAnnouncements(\App\Models\Pelatihan $pelatihan): JsonResponse
    {
        $pengumuman = PengumumanPelatihan::where('pelatihan_id', $pelatihan->id)
            ->where('is_private', false)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengumuman publik berhasil diambil.',
            'data' => $pengumuman
        ]);
    }
}
