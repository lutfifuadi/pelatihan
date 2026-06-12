<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\language\LanguageController;

// ===== SEO ROUTES =====
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Landing\RegistrationController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\KoordinatorController;
use App\Http\Controllers\Admin\PelatihanController;
use App\Http\Controllers\Admin\DinasController;
use App\Http\Controllers\Admin\KelurahanController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\WhatsAppGatewayController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\KoordinatorRegisterController;
use App\Http\Controllers\Peserta\PesertaFormController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Illuminate\Support\Facades\Auth;

// Main Page Route
Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// ===== Koordinator Registration =====
Route::get('/daftar-koordinator', [KoordinatorRegisterController::class, 'showForm'])->name('koordinator.register');
Route::post('/daftar-koordinator', [KoordinatorRegisterController::class, 'register']);
Route::get('/daftar-koordinator/sukses', [KoordinatorRegisterController::class, 'sukses'])->name('koordinator.register.sukses');

// ===== PUBLIC: Detail Pelatihan (untuk SEO & Sitemap) =====
Route::get('/pelatihan/{pelatihan}', [\App\Http\Controllers\PelatihanController::class, 'show'])
    ->name('pelatihan.show');

// ===== Landing Page Registration =====
Route::get('/daftar', function () {
    return redirect('/#beranda');
})->name('landing.register.form');
Route::post('/daftar', [RegistrationController::class, 'register'])->name('landing.register');
Route::post('/daftar/cek-nik', [RegistrationController::class, 'checkNik'])->name('landing.check-nik');
Route::post('/daftar/cek-wa', [RegistrationController::class, 'checkWa'])->name('landing.check-wa');
Route::get('/daftar/sukses', [RegistrationController::class, 'sukses'])->name('landing.sukses')->middleware('auth');

// Home route - Jetstream redirects here after login/register
Route::get('/home', function () {
    $user = Auth::user();
    if (!$user) return redirect('/login');

    // Jika baru registrasi, arahkan ke halaman sukses
    if (session()->pull('new_registration', false)) {
        return redirect()->route('landing.sukses');
    }

    return match ($user->role) {
        'admin' => redirect()->route('dashboard.admin'),
        'instruktur' => redirect()->route('dashboard.instruktur'),
        'koordinator' => redirect()->route('dashboard.koordinator'),
        default => redirect()->route('dashboard.peserta'),
    };
})->name('home');

// ===== API Routes untuk dependent dropdown =====
Route::get('/api/kelurahan', function (Illuminate\Http\Request $request) {
    $kecamatanId = $request->get('kecamatan_id');
    if (!$kecamatanId) {
        return response()->json([]);
    }
    $kelurahans = App\Models\Kelurahan::where('kecamatan_id', $kecamatanId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name']);
    return response()->json($kelurahans);
})->name('api.kelurahan');

// ===== DASHBOARD (Protected - via Jetstream Fortify) =====
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });

    // Instruktur only
    Route::middleware(['role:instruktur'])->group(function () {
        Route::get('/dashboard/instruktur', [DashboardController::class, 'instruktur'])->name('dashboard.instruktur');
    });

    // Koordinator only
    Route::middleware(['role:koordinator'])->group(function () {
        Route::get('/dashboard/koordinator', [DashboardController::class, 'koordinator'])->name('dashboard.koordinator');
    });

    // Peserta only
    Route::middleware(['role:peserta'])->group(function () {
        Route::get('/dashboard/peserta', [DashboardController::class, 'peserta'])->name('dashboard.peserta');

        // Form pendaftaran di dalam dashboard/peserta/
        Route::prefix('dashboard/peserta')->name('dashboard.peserta.')->group(function () {
            Route::get('/form-pendaftaran', function () {
                $u = auth()->user();
                $profile = \App\Models\PesertaProfile::where('user_id', $u->id)->first();
                $kecamatans = \App\Models\Kecamatan::orderBy('name')->get();
                $lockKota = \App\Models\Setting::where('key', 'lock_kota')->value('value') ?? 'BANDUNG';
                $lockProvinsi = \App\Models\Setting::where('key', 'lock_provinsi')->value('value') ?? 'Jawa Barat';
                $userData = [
                    'name' => $u->name,
                    'nik' => $u->nik,
                    'whatsapp' => $u->whatsapp,
                    'email' => $u->email,
                    // Data profile dari database
                    'jenis_kelamin' => $profile->jenis_kelamin ?? '',
                    'tempat_lahir' => $profile->tempat_lahir ?? '',
                    'tanggal_lahir' => $profile->tanggal_lahir ?? '',
                    'bulan_lahir' => $profile->bulan_lahir ?? '',
                    'tahun_lahir' => $profile->tahun_lahir ?? '',
                    'alamat_ktp' => $profile->alamat_ktp ?? '',
                    'rt' => $profile->rt ?? '',
                    'rw' => $profile->rw ?? '',
                    'kelurahan' => $profile->kelurahan ?? '',
                    'kecamatan' => $profile->kecamatan ?? '',
                    'kota' => $lockKota,
                    'provinsi' => $lockProvinsi,
                    'kodepos' => $profile->kodepos ?? '',
                    'link_medsos' => $profile->link_medsos ?? '',
                ];
                return view('content.dashboard.peserta.form-pendaftaran', ['user' => $userData, 'kecamatans' => $kecamatans]);
            })->name('form-pendaftaran');
            Route::post('/form-pendaftaran', [PesertaFormController::class, 'store'])->name('form-pendaftaran.store');
            Route::post('/save-tab1', [PesertaFormController::class, 'saveTab1'])->name('save-tab1');

            // Form Tahap 3 - Pendidikan & Pekerjaan
            Route::get('/form-pendidikan', [PesertaFormController::class, 'pendidikan'])->name('form-pendidikan');
            Route::post('/form-pendidikan', [PesertaFormController::class, 'savePendidikan'])->name('form-pendidikan.store');

            // Form Tahap 4 - Minat Pelatihan
            Route::get('/form-minat', [PesertaFormController::class, 'minat'])->name('form-minat');
            Route::post('/form-minat', [PesertaFormController::class, 'saveMinat'])->name('form-minat.store');

            // Form Tahap 5 - Dokumen & Konfirmasi
            Route::get('/form-dokumen', [PesertaFormController::class, 'dokumen'])->name('form-dokumen');
            Route::post('/form-dokumen', [PesertaFormController::class, 'saveDokumen'])->name('form-dokumen.store');
        });
    });

    // ===== ADMIN MANAGEMENT (Admin only) =====
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Kecamatan
        Route::resource('kecamatan', KecamatanController::class);

        // Pelatihan
        Route::resource('pelatihan', PelatihanController::class);
        Route::get('pelatihan/{pelatihan}/peserta', [PelatihanController::class, 'show'])->name('pelatihan.peserta');

        // Dinas
        Route::resource('dinas', DinasController::class);

        // Kelurahan
        Route::resource('kelurahan', KelurahanController::class);

        // Peserta
        Route::get('peserta', [PesertaController::class, 'index'])->name('peserta.index');
        Route::get('peserta/{user}', [PesertaController::class, 'show'])->name('peserta.show');
        Route::delete('peserta/{user}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

        // WhatsApp Gateway
        Route::get('whatsapp-gateway', [WhatsAppGatewayController::class, 'index'])->name('whatsapp-gateway.index');
        Route::post('whatsapp-gateway', [WhatsAppGatewayController::class, 'update'])->name('whatsapp-gateway.update');
        Route::post('whatsapp-gateway/test', [WhatsAppGatewayController::class, 'test'])->name('whatsapp-gateway.test');

        // Settings - Branding
        Route::get('settings/branding', [SettingController::class, 'branding'])->name('settings.branding');
        Route::post('settings/branding', [SettingController::class, 'updateBranding'])->name('settings.branding.update');

        // Settings - SEO
        Route::get('settings/seo', [SettingController::class, 'seo'])->name('settings.seo');
        Route::post('settings/seo', [SettingController::class, 'updateSeo'])->name('settings.seo.update');

        // FAQ Management
        Route::resource('faqs', FaqController::class)->parameters(['faqs' => 'faq']);

        // Koordinator
        Route::get('koordinator/pending', [KoordinatorController::class, 'pending'])->name('koordinator.pending');
        Route::post('koordinator/{koordinator}/approve', [KoordinatorController::class, 'approve'])->name('koordinator.approve');
        Route::post('koordinator/{koordinator}/reject', [KoordinatorController::class, 'reject'])->name('koordinator.reject');
        Route::resource('koordinator', KoordinatorController::class);
    });
});

// ===== ADMIN LOGIN (separate from regular user login) =====
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest admin routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login']);
        Route::get('/forgot-password', [AdminLoginController::class, 'showForgotForm'])->name('password.request');
    });

    // Authenticated admin routes
    Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('dashboard.admin');
        })->name('dashboard');
    });
});

// Old auth routes - redirect to new Jetstream routes
Route::get('/auth/login-basic', function () {
    return redirect('/login');
})->name('auth-login-basic');

Route::get('/auth/register-basic', function () {
    return redirect('/register');
})->name('auth-register-basic');
