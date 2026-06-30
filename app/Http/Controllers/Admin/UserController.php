<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use App\Models\PesertaProfile;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Tampilkan form tambah user baru.
     */
    public function create()
    {
        return view('content.admin.users.create');
    }

    /**
     * Simpan user baru yang dibuat oleh admin.
     */
    public function store(StoreUserRequest $request)
    {
        // Normalisasi nomor WhatsApp: hapus spasi, tanda +, dan strip/dash
        $whatsapp = preg_replace('/[\s\+\-]/', '', $request->whatsapp);

        // Generate password otomatis: Plh@ + nomor whatsapp yang sudah dinormalisasi
        $defaultPassword = 'Plh@' . $whatsapp;

        // Bungkus seluruh proses penulisan ke database (User, PesertaProfile jika ada, dan ActivityLogger) ke dalam DB transaction
        $user = DB::transaction(function() use ($request, $whatsapp, $defaultPassword) {
            // Buat user baru
            $user = User::create([
                'name'                 => $request->name,
                'email'                => $request->email,
                'whatsapp'             => $whatsapp,
                'role'                 => $request->role,
                'is_active'            => (bool) $request->is_active,
                'nik'                  => $request->nik,
                'password'             => Hash::make($defaultPassword),
                'must_change_password' => true,
            ]);

            // Jika role user yang dibuat adalah 'peserta', maka buat juga record profil peserta di tabel peserta_profiles secara otomatis
            if ($user->role === 'peserta') {
                PesertaProfile::create([
                    'user_id'       => $user->id,
                    'nama_lengkap'  => $user->name,
                    'nik'           => $user->nik,
                    'whatsapp'      => $user->whatsapp,
                    'email'         => $user->email,
                    'is_completed'  => false,
                ]);
            }

            // Catat activity log
            ActivityLogger::log(
                action: 'created',
                subjectType: 'User',
                subjectId: $user->id,
                subjectName: $user->name,
                description: "User baru {$user->name} ({$user->role}) berhasil ditambahkan oleh admin",
                newValues: [
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'whatsapp'  => $user->whatsapp,
                    'role'      => $user->role,
                    'is_active' => $user->is_active,
                    'nik'       => $user->nik,
                ]
            );

            return $user;
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User <strong>{$user->name}</strong> berhasil ditambahkan.")
            ->with('new_user_password', $defaultPassword)
            ->with('new_user_name', $user->name);
    }

    /**
     * Tampilkan daftar user dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');

        $query = User::query();

        // Pencarian: name, email, nik, whatsapp
        $query->when($search, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('whatsapp', 'like', '%' . $search . '%');
            });
        });

        // Filter role: admin, instruktur, koordinator, peserta
        $query->when($request->filled('role'), function ($q) use ($role) {
            $q->where('role', $role);
        });

        // Filter status: is_active
        if ($request->filled('status')) {
            if ($status === '1' || $status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === '0' || $status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        $users = $query->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $rows = view('content.admin.users._table_rows', compact('users'))->render();
            $pagination = $users->hasPages() ? $users->links()->render() : '';
            return response()->json([
                'rows' => $rows,
                'pagination' => $pagination,
            ]);
        }

        return view('content.admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    /**
     * Ubah status aktif/nonaktif user.
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak bisa menonaktifkan akun Anda sendiri.'
                ], 403);
            }
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $oldData = $user->getAttributes();
        $user->is_active = !$user->is_active;
        $user->save();
        $newData = $user->getAttributes();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Log aktivitas jika ada ActivityLogger
        ActivityLogger::log(
            action: 'updated',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $user->name,
            description: "Status user {$user->name} {$statusText}",
            oldValues: $oldData,
            newValues: $newData
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status user {$user->name} berhasil {$statusText}.",
                'is_active' => $user->is_active
            ]);
        }

        return back()->with('success', "Status user {$user->name} berhasil {$statusText}.");
    }

    /**
     * Reset password user ke nomor HP/WhatsApp.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Cegah admin mereset password dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa mereset password akun Anda sendiri.');
        }

        if ($user->role === 'peserta') {
            $user->password = Hash::make('pelatihanku2026');
            $user->must_change_password = true;
            $user->save();

            ActivityLogger::log(
                action: 'updated',
                subjectType: 'User',
                subjectId: $user->id,
                subjectName: $user->name,
                description: "Password peserta {$user->name} telah direset ke default: pelatihanku2026",
                oldValues: ['password' => '***'],
                newValues: ['password' => '***']
            );

            // Perhatian: test PHPUnit memverifikasi pesan sukses menggunakan nama original (dengan title case seperti "Peserta Uji"), namun nama di database dikonversi ke uppercase oleh mutator.
            // Gunakan $user->name (yang sudah uppercase) agar dinamis dan tepat.
            $displayName = $user->name;
            return back()->with('success', "Password peserta {$displayName} telah direset ke default: pelatihanku2026");
        }

        // Cari nomor HP: prioritas whatsapp, fallback ke phone
        $phoneNumber = $user->whatsapp ?? $user->phone;

        if (!$phoneNumber) {
            return back()->with('error', "User {$user->name} tidak memiliki nomor HP untuk dijadikan password.");
        }

        // Bersihkan nomor dari karakter non-digit
        $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Validasi minimal 8 digit
        if (strlen($cleanedPhoneNumber) < 8) {
            return back()->with('error', "Nomor HP user {$user->name} tidak valid (minimal 8 digit).");
        }

        // Set password baru
        $user->password = Hash::make($cleanedPhoneNumber);
        $user->must_change_password = true;
        $user->save();

        // Catat log aktivitas
        ActivityLogger::log(
            action: 'updated',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $user->name,
            description: "Password user {$user->name} telah direset ke nomor HP",
            oldValues: ['password' => '***'],
            newValues: ['password' => '***']
        );

        $displayName = $user->name;
        return back()->with('success', "Password {$displayName} telah direset ke nomor HP.");
    }

    /**
     * Reset semua password peserta secara massal.
     */
    public function resetAllPeserta(Request $request)
    {
        $count = User::where('role', 'peserta')->count();

        if ($count === 0) {
            return back()->with('error', 'Tidak ada data peserta untuk direset.');
        }

        \App\Models\User::where('role', 'peserta')->update([
            'password' => \Illuminate\Support\Facades\Hash::make('pelatihanku2026'),
            'must_change_password' => true
        ]);

        ActivityLogger::log(
            action: 'updated',
            subjectType: 'User',
            subjectId: null,
            subjectName: 'Semua Peserta',
            description: "Mereset password semua peserta ({$count} peserta) ke default pelatihanku2026 secara massal"
        );

        return back()->with('success', "Berhasil mereset password {$count} peserta ke default pelatihanku2026 secara massal.");
    }

    /**
     * Hapus user secara permanen.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $oldData = $user->getAttributes();
        $name = $user->name;
        $user->delete();

        // Log aktivitas
        ActivityLogger::log(
            action: 'deleted',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $name,
            description: "User {$name} berhasil dihapus permanen",
            oldValues: $oldData
        );

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}
