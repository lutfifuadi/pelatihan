<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePesertaBiodataRequest;
use App\Models\AuditLog;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PesertaProfile;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $filterPelatihan = $request->get('filter_pelatihan', 'all');

        $allowedSort = ['name', 'nik', 'whatsapp', 'created_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'name';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';

        $pesertas = User::where('role', 'peserta')
            ->with('kecamatan', 'kelurahan', 'pesertaProfile.pelatihan')
            ->when($search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->when($filterPelatihan && $filterPelatihan !== 'all', function ($q) use ($filterPelatihan) {
                if ($filterPelatihan === 'sudah') {
                    $q->whereHas('pesertaProfile', fn($q) => $q->whereNotNull('pelatihan_id'));
                } elseif ($filterPelatihan === 'belum') {
                    $q->whereDoesntHave('pesertaProfile', fn($q) => $q->whereNotNull('pelatihan_id'));
                }
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $rows = view('content.admin.peserta._table_rows', compact('pesertas', 'sortBy', 'sortDir', 'search', 'filterPelatihan'))->render();
            $pagination = $pesertas->hasPages() ? $pesertas->links()->render() : '';
            return response()->json(['rows' => $rows, 'pagination' => $pagination]);
        }

        return view('content.admin.peserta.index', compact('pesertas', 'sortBy', 'sortDir', 'search', 'filterPelatihan'));
    }

    public function show(User $peserta)
    {
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        $peserta->load('kecamatan', 'kelurahan', 'pesertaProfile', 'enrollments.pelatihan');
        return view('content.admin.peserta.show', compact('peserta'));
    }

    /**
     * Menampilkan form edit biodata peserta.
     *
     * Load semua relasi yang diperlukan dan siapkan data dropdown
     * untuk ditampilkan di view admin.peserta.edit-biodata.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function editBiodata(User $user): View
    {
        // Pastikan yang diakses adalah peserta
        if ($user->role !== 'peserta') {
            abort(404);
        }

        // Load relasi yang dibutuhkan form
        $user->load('pesertaProfile', 'kecamatan', 'kelurahan');

        // Data dropdown wilayah
        $kecamatanList = Kecamatan::orderBy('name')->get();
        $kelurahanList = $user->kecamatan_id
            ? Kelurahan::where('kecamatan_id', $user->kecamatan_id)->orderBy('name')->get()
            : collect();

        // Data dropdown profil (dari konstanta PesertaProfile)
        $pendidikanList = PesertaProfile::PENDIDIKAN_OPTIONS;
        $pekerjaanList  = PesertaProfile::PEKERJAAN_OPTIONS;
        $minatList      = PesertaProfile::MINAT_OPTIONS;

        // Ambil profil (bisa null jika belum diisi)
        $profile = $user->pesertaProfile;

        // Normalisasi data dari database agar sesuai dengan form input
        if ($profile) {
            // 1. Jenis Kelamin
            if ($profile->jenis_kelamin) {
                $jk = strtoupper(trim($profile->jenis_kelamin));
                if (in_array($jk, ['L', 'LAKI-LAKI'])) {
                    $profile->jenis_kelamin = 'Laki-laki';
                } elseif (in_array($jk, ['P', 'PEREMPUAN'])) {
                    $profile->jenis_kelamin = 'Perempuan';
                }
            }

            // 2. Bulan Lahir (string ke numeric)
            if ($profile->bulan_lahir) {
                $monthMap = [
                    'januari' => 1, 'jan' => 1, '01' => 1, '1' => 1,
                    'februari' => 2, 'feb' => 2, '02' => 2, '2' => 2,
                    'maret' => 3, 'mar' => 3, '03' => 3, '3' => 3,
                    'april' => 4, 'apr' => 4, '04' => 4, '4' => 4,
                    'mei' => 5, '05' => 5, '5' => 5,
                    'juni' => 6, 'jun' => 6, '06' => 6, '6' => 6,
                    'juli' => 7, 'jul' => 7, '07' => 7, '7' => 7,
                    'agustus' => 8, 'agu' => 8, '08' => 8, '8' => 8,
                    'september' => 9, 'sep' => 9, '09' => 9, '9' => 9,
                    'oktober' => 10, 'okt' => 10, '10' => 10,
                    'november' => 11, 'nov' => 11, '11' => 11,
                    'desember' => 12, 'des' => 12, '12' => 12,
                ];
                $lowerMonth = strtolower(trim($profile->bulan_lahir));
                if (isset($monthMap[$lowerMonth])) {
                    $profile->bulan_lahir = $monthMap[$lowerMonth];
                }
            }

            // 3. Status Pekerjaan
            if ($profile->status_pekerjaan) {
                $statusMap = [
                    'BELUM BEKERJA' => 'Tidak Bekerja',
                    'PELAJAR/MAHASISWA' => 'Pelajar/Mahasiswa',
                    'WIRAUSAHA' => 'Wirausaha',
                    'BEKERJA' => 'Karyawan Swasta',
                ];
                $statusPek = strtoupper(trim($profile->status_pekerjaan));
                if (isset($statusMap[$statusPek])) {
                    $profile->status_pekerjaan = $statusMap[$statusPek];
                }
            }
        }

        // Ambil daftar pelatihan aktif untuk dropdown
        $pelatihanList = \App\Models\Pelatihan::where('is_active', true)->orderBy('batch')->get();

        return view('admin.peserta.edit-biodata', compact(
            'user',
            'profile',
            'kecamatanList',
            'kelurahanList',
            'pendidikanList',
            'pekerjaanList',
            'minatList',
            'pelatihanList'
        ));
    }

    /**
     * Menyimpan perubahan biodata peserta yang dilakukan oleh admin.
     *
     * Proses:
     * 1. Simpan data lama untuk keperluan audit log
     * 2. Update model User dengan field yang relevan
     * 3. Update atau create PesertaProfile
     * 4. Handle upload foto_profil dan scan_ktp jika ada file baru
     * 5. Catat perubahan ke AuditLog
     * 6. Redirect ke halaman detail peserta dengan flash message
     *
     * @param  \App\Http\Requests\Admin\UpdatePesertaBiodataRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBiodata(UpdatePesertaBiodataRequest $request, User $user)
    {
        // Pastikan yang diakses adalah peserta
        if ($user->role !== 'peserta') {
            abort(404);
        }

        try {
            DB::transaction(function () use ($request, $user) {

                // ──────────────────────────────────────────────
                // 1. Simpan data lama untuk audit log
                // ──────────────────────────────────────────────
                $oldUserData = $user->only([
                    'name', 'email', 'phone', 'whatsapp', 'bio', 'nik',
                    'status_tokoh', 'sumber_informasi', 'sumber_informasi_detail',
                    'kecamatan_id', 'kelurahan_id',
                ]);
                $oldProfileData = $user->pesertaProfile?->only([
                    'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                    'bulan_lahir', 'tahun_lahir', 'alamat_ktp', 'rt', 'rw',
                    'kelurahan_id', 'kelurahan', 'kecamatan', 'kota', 'provinsi',
                    'kodepos', 'link_medsos', 'pendidikan_terakhir', 'nama_institusi',
                    'jurusan', 'tahun_lulus', 'status_pekerjaan', 'nama_perusahaan',
                    'bidang_minat', 'tujuan_pelatihan', 'preferensi_jadwal', 'preferensi_mode',
                    'foto_profil', 'scan_ktp',
                ]) ?? [];

                // ──────────────────────────────────────────────
                // 2. Update model User
                // ──────────────────────────────────────────────
                $user->update($request->getUserData());

                // ──────────────────────────────────────────────
                // 3. Update atau create PesertaProfile
                // ──────────────────────────────────────────────
                $profile = $user->pesertaProfile ?? new PesertaProfile(['user_id' => $user->id]);

                // ──────────────────────────────────────────────
                // 4. Handle upload foto_profil
                // ──────────────────────────────────────────────
                if ($request->hasFile('foto_profil')) {
                    // Hapus file lama jika ada
                    if ($profile->foto_profil && Storage::disk('public')->exists($profile->foto_profil)) {
                        Storage::disk('public')->delete($profile->foto_profil);
                    }
                    $profile->foto_profil = $request->file('foto_profil')
                        ->store('peserta/foto', 'public');
                }

                // ──────────────────────────────────────────────
                // 5. Handle upload scan_ktp
                // ──────────────────────────────────────────────
                if ($request->hasFile('scan_ktp')) {
                    // Hapus file lama jika ada
                    if ($profile->scan_ktp && Storage::disk('public')->exists($profile->scan_ktp)) {
                        Storage::disk('public')->delete($profile->scan_ktp);
                    }
                    $profile->scan_ktp = $request->file('scan_ktp')
                        ->store('peserta/ktp', 'public');
                }

                // Isi data profil dan simpan
                $profile->fill($request->getProfileData())->save();

                // ──────────────────────────────────────────────
                // 6. Catat perubahan ke AuditLog
                // ──────────────────────────────────────────────
                $newUserData    = $request->getUserData();
                $newProfileData = $request->getProfileData();

                // Hanya catat field yang benar-benar berubah (dirty fields)
                $changedUser    = array_diff_assoc($newUserData, $oldUserData);
                $changedProfile = array_diff_assoc($newProfileData, $oldProfileData);

                try {
                    AuditLog::record(
                        action: 'update_biodata_by_admin',
                        subjectType: 'User',
                        subjectId: $user->id,
                        oldValues: [
                            'user'    => array_intersect_key($oldUserData, $changedUser),
                            'profile' => array_intersect_key($oldProfileData, $changedProfile),
                        ],
                        newValues: [
                            'user'    => $changedUser,
                            'profile' => $changedProfile,
                        ],
                        notes: 'Biodata diubah oleh admin: ' . auth()->user()->name,
                    );
                } catch (\Exception $auditException) {
                    // Audit log gagal tidak menghentikan proses utama
                    Log::error('[PesertaController] Gagal mencatat audit log updateBiodata', [
                        'user_id'  => $user->id,
                        'admin_id' => auth()->id(),
                        'error'    => $auditException->getMessage(),
                    ]);
                }
            });

        } catch (\Exception $e) {
            Log::error('[PesertaController] Gagal updateBiodata peserta', [
                'user_id'  => $user->id,
                'admin_id' => auth()->id(),
                'error'    => $e->getMessage(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan perubahan: ' . $e->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan perubahan. Silakan coba lagi.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Biodata peserta berhasil diperbarui.',
            ]);
        }

        return redirect()
            ->route('admin.peserta.show', $user)
            ->with('success', 'Biodata peserta berhasil diperbarui.');
    }

    public function destroy(User $peserta)
    {
        if ($peserta->role !== 'peserta') {
            abort(404);
        }

        $oldData = $peserta->getAttributes();
        $nama = $peserta->name;
        $peserta->delete();

        ActivityLogger::log(
            action: 'deleted',
            subjectType: 'Peserta',
            subjectId: $peserta->id,
            subjectName: $nama,
            description: "Peserta {$nama} berhasil dihapus",
            oldValues: $oldData,
        );

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
