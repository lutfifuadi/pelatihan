<?php

use App\Http\Controllers\InstallerController;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

// ===== WEB INSTALLER ROUTES =====
Route::prefix('install')->name('installer.')->middleware('redirect.if.installed')->group(function () {
    Route::get('/', [InstallerController::class, 'step1'])->name('step1');
    Route::get('/step2', [InstallerController::class, 'step2'])->name('step2');
    Route::post('/step2', [InstallerController::class, 'step2Submit'])->name('step2Submit');
    Route::post('/step2/test', [InstallerController::class, 'testConnection'])->name('step2.test');
    Route::get('/step3', [InstallerController::class, 'step3'])->name('step3');
    Route::post('/process', [InstallerController::class, 'process'])->name('process');
    Route::get('/progress', [InstallerController::class, 'progress'])->name('progress');
});

// ===== SEO ROUTES =====
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DinasController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FormFieldConfigController;
use App\Http\Controllers\Admin\FormOptionController;
use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\KelurahanController;
use App\Http\Controllers\Admin\KoordinatorController;
use App\Http\Controllers\Admin\NotificationAdminController;
use App\Http\Controllers\Admin\PelatihanController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SystemLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhatsAppGatewayController;
use App\Http\Controllers\Admin\WhatsAppSupportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\KoordinatorRegisterController;
use App\Http\Controllers\KtaMemberController;
use App\Http\Controllers\Landing\RegistrationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\Peserta\PesertaFormController;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Http\Request;
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

// ===== PUBLIC: Semua Pelatihan =====
Route::get('/pelatihan', [App\Http\Controllers\PelatihanController::class, 'index'])->name('pelatihan.index');

// ===== PUBLIC: Detail Pelatihan (untuk SEO & Sitemap) =====
Route::get('/pelatihan/{pelatihan}', [App\Http\Controllers\PelatihanController::class, 'show'])
    ->name('pelatihan.show');

// ===== Landing Page Registration =====
Route::get('/daftar', function () {
    return redirect('/#beranda');
})->name('landing.register.form');
Route::post('/daftar', [RegistrationController::class, 'register'])->name('landing.register')->middleware('throttle:10,1');
Route::post('/daftar/cek-nik', [RegistrationController::class, 'checkNik'])->name('landing.check-nik')->middleware('throttle:10,1');
Route::post('/daftar/cek-wa', [RegistrationController::class, 'checkWa'])->name('landing.check-wa')->middleware('throttle:10,1');
Route::get('/daftar/sukses', [RegistrationController::class, 'sukses'])->name('landing.sukses')->middleware('auth');

// ===== MAINTENANCE PAGE (public) =====
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');

// Home route - Jetstream redirects here after login/register
Route::get('/home', function () {
    $user = Auth::user();
    if (! $user) {
        return redirect('/login');
    }

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
Route::get('/api/kelurahan', function (Request $request) {
    $kecamatanId = $request->get('kecamatan_id');
    if (! $kecamatanId) {
        return response()->json([]);
    }
    $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'kodepos']);

    return response()->json($kelurahans);
})->name('api.kelurahan');

// Public API: Daftar Koordinator untuk autocomplete registrasi
Route::get('/api/koordinator', function (Request $request) {
    $query = $request->get('q');
    $koordinator = User::where('role', 'koordinator')
        ->where('is_active', true);

    if ($query && strlen($query) >= 3) {
        $koordinator->where(function ($q) use ($query) {
            $q->where('name', 'like', '%'.$query.'%')
                ->orWhere('nik', 'like', '%'.$query.'%');
        });
    }

    return response()->json(
        $koordinator->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'nik'])
    );
})->name('api.koordinator');

// ===== DASHBOARD (Protected - via Jetstream Fortify) =====
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Impersonate Leave
    Route::post('/impersonate/leave', [ImpersonateController::class, 'leave'])
        ->name('impersonate.leave');

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
            // Halaman 1: Data Pribadi
            Route::get('/form-pendaftaran', [PesertaFormController::class, 'formPendaftaran'])->name('form-pendaftaran');
            Route::post('/form-pendaftaran', [PesertaFormController::class, 'store'])->name('form-pendaftaran.store');
            Route::post('/save-tab1', [PesertaFormController::class, 'saveTab1'])->name('save-tab1');

            // Halaman 2: Alamat & Kontak
            Route::get('/form-alamat', [PesertaFormController::class, 'formAlamat'])->name('form-alamat');
            Route::post('/form-alamat', [PesertaFormController::class, 'storeAlamat'])->name('form-alamat.store');

            // Form Tahap 3 - Pendidikan & Pekerjaan
            Route::get('/form-pendidikan', [PesertaFormController::class, 'pendidikan'])->name('form-pendidikan');
            Route::post('/form-pendidikan', [PesertaFormController::class, 'savePendidikan'])->name('form-pendidikan.store');

            // Form Tahap 4 - Minat Pelatihan
            Route::get('/form-minat', [PesertaFormController::class, 'minat'])->name('form-minat');
            Route::post('/form-minat', [PesertaFormController::class, 'saveMinat'])->name('form-minat.store');

            // Form Tahap 5 - Dokumen & Pertanyaan
            Route::get('/form-dokumen', [PesertaFormController::class, 'dokumen'])->name('form-dokumen');
            Route::post('/form-dokumen', [PesertaFormController::class, 'saveDokumen'])->name('form-dokumen.store');

            // Form Tahap 6 - Review Data & Submit Final
            Route::get('/form-review', [PesertaFormController::class, 'review'])->name('form-review');
            Route::post('/form-review', [PesertaFormController::class, 'submitFinal'])->name('form-review.submit');

            // Halaman Sukses setelah submit final
            Route::get('/pendaftaran-sukses', [PesertaFormController::class, 'pendaftaranSukses'])->name('pendaftaran-sukses');

            // Halaman Status Pendaftaran
            Route::get('/status', [PesertaFormController::class, 'statusPendaftaran'])->name('status');

            // Halaman Profil
            Route::get('/profil', [PesertaFormController::class, 'profil'])->name('profil');

            // Halaman Notifikasi Peserta
            Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');

            // Halaman Upload Foto
            Route::get('/upload-foto', function () {
                return view('content.dashboard.peserta.upload-foto');
            })->name('upload-foto');
        });
    });

    // ===== NOTIFIKASI ROUTES =====
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences');
    Route::post('/notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.preferences.update');

    // ===== ADMIN MANAGEMENT (Admin only) =====
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Impersonate Take
        Route::post('users/{user}/impersonate', [ImpersonateController::class, 'take'])
            ->middleware('can.impersonate')
            ->name('users.impersonate');

        // Users Management
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users/reset-all-peserta', [UserController::class, 'resetAllPeserta'])->name('users.reset-all-peserta');
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // KTA Members Management
        Route::resource('kta-members', KtaMemberController::class);

        // Kecamatan
        Route::resource('kecamatan', KecamatanController::class);

        // Pelatihan
        Route::resource('pelatihan', PelatihanController::class);
        Route::get('pelatihan/{pelatihan}/peserta', [PelatihanController::class, 'show'])->name('pelatihan.peserta');

        // Dinas
        Route::resource('dinas', DinasController::class)->parameter('dinas', 'dinas');

        // Kelurahan
        Route::resource('kelurahan', KelurahanController::class);

        // Peserta
        Route::get('peserta', [PesertaController::class, 'index'])->name('peserta.index');
        Route::get('peserta/{peserta}', [PesertaController::class, 'show'])->name('peserta.show');
        Route::delete('peserta/{peserta}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

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

        // Settings - Landing Page Content
        Route::get('settings/landing', [SettingController::class, 'landing'])->name('settings.landing');
        Route::post('settings/landing', [SettingController::class, 'updateLanding'])->name('settings.landing.update');

        // Settings - Maintenance
        Route::get('settings/maintenance', [SettingController::class, 'maintenance'])->name('settings.maintenance');
        Route::post('settings/maintenance', [SettingController::class, 'updateMaintenance'])->name('settings.maintenance.update');

        // FAQ Management
        Route::resource('faqs', FaqController::class)->parameters(['faqs' => 'faq']);

        // Enrollment (Pendaftaran Pelatihan - Approve/Reject/Waitlist)
        Route::get('enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('enrollments/pelatihan/{pelatihan}', [EnrollmentController::class, 'index'])->name('enrollments.pelatihan');
        Route::get('enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
        Route::post('enrollments/{enrollment}/approve', [EnrollmentController::class, 'approve'])->name('enrollments.approve');
        Route::post('enrollments/{enrollment}/reject', [EnrollmentController::class, 'reject'])->name('enrollments.reject');
        Route::post('enrollments/{enrollment}/waitlist', [EnrollmentController::class, 'waitlist'])->name('enrollments.waitlist');
        Route::post('enrollments/{enrollment}/promote', [EnrollmentController::class, 'promote'])->name('enrollments.promote');
        Route::post('enrollments/pelatihan/{pelatihan}/approve-all', [EnrollmentController::class, 'approveAll'])->name('enrollments.approve-all');
        Route::post('enrollments/pelatihan/{pelatihan}/reject-all', [EnrollmentController::class, 'rejectAll'])->name('enrollments.reject-all');
        Route::post('enrollments/{enrollment}/reset', [EnrollmentController::class, 'reset'])->name('enrollments.reset');
        Route::post('enrollments/{enrollment}/change-status', [EnrollmentController::class, 'changeStatus'])->name('enrollments.change-status');
        Route::post('enrollments/{enrollment}/transfer', [EnrollmentController::class, 'transfer'])->name('enrollments.transfer');
        Route::post('enrollments/{enrollment}/confirm-wa-chat', [EnrollmentController::class, 'confirmWaChat'])->name('enrollments.confirm-wa-chat');
        Route::post('enrollments/{enrollment}/confirm-newbimma-valid', [EnrollmentController::class, 'confirmNewbimmaValid'])->name('enrollments.confirm-newbimma-valid');
        Route::post('enrollments/{enrollment}/reject-newbimma-invalid', [EnrollmentController::class, 'rejectNewbimmaInvalid'])->name('enrollments.reject-newbimma-invalid');
        Route::post('enrollments/{enrollment}/generate-verification-code', [EnrollmentController::class, 'generateVerificationCode'])->name('enrollments.generate-verification-code');
        Route::get('enrollments/{enrollment}/available-pelatihans', [EnrollmentController::class, 'getAvailablePelatihans'])->name('enrollments.available-pelatihans');
        Route::post('enrollments/generate-all-verification-codes', [EnrollmentController::class, 'generateAllVerificationCodes'])->name('enrollments.generate-all-verification-codes');

        // Absensi
        Route::get('attendances/{pelatihan}', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('attendances/{pelatihan}', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendances/{pelatihan}/rapport', [AttendanceController::class, 'rapport'])->name('attendances.rapport');

        // Sertifikat
        Route::get('certificates', [CertificateController::class, 'index'])->name('certificates.index');
        Route::get('certificates/create/{pelatihan}', [CertificateController::class, 'create'])->name('certificates.create');
        Route::post('certificates', [CertificateController::class, 'store'])->name('certificates.store');
        Route::post('certificates/batch/{pelatihan}', [CertificateController::class, 'generateBatch'])->name('certificates.batch');
        Route::get('certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
        Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

        // Koordinator
        Route::get('koordinator/pending', [KoordinatorController::class, 'pending'])->name('koordinator.pending');
        Route::post('koordinator/{koordinator}/approve', [KoordinatorController::class, 'approve'])->name('koordinator.approve');
        Route::post('koordinator/{koordinator}/reject', [KoordinatorController::class, 'reject'])->name('koordinator.reject');
        Route::post('koordinator/{koordinator}/toggle-status', [KoordinatorController::class, 'toggleStatus'])->name('koordinator.toggle-status');
        Route::resource('koordinator', KoordinatorController::class);

        // Notifikasi Admin
        Route::get('notifications', [NotificationAdminController::class, 'index'])->name('notifications.index');

        // Broadcast (harus sebelum {notification} agar 'broadcast' tidak dianggap sebagai ID)
        Route::get('notifications/broadcast', [NotificationAdminController::class, 'broadcast'])->name('notifications.broadcast');
        Route::get('notifications/broadcast/count', [NotificationAdminController::class, 'estimateCount'])->name('notifications.broadcast.count');
        Route::post('notifications/broadcast/send', [NotificationAdminController::class, 'sendBroadcast'])->name('notifications.broadcast.send');

        Route::get('notifications/count-filtered', [NotificationAdminController::class, 'countFiltered'])->name('notifications.count-filtered');
        Route::get('notifications/{notification}', [NotificationAdminController::class, 'show'])->name('notifications.show');
        Route::post('notifications/{notification}/resend', [NotificationAdminController::class, 'resend'])->name('notifications.resend');
        Route::delete('notifications/{notification}', [NotificationAdminController::class, 'destroy'])->name('notifications.destroy');
        Route::post('notifications/destroy-all', [NotificationAdminController::class, 'destroyAll'])->name('notifications.destroy-all');

        // System Logs
        Route::get('system/logs', [SystemLogController::class, 'index'])->name('system-logs.index');
        Route::get('system/logs/data', [SystemLogController::class, 'getLogs'])->name('system-logs.data');
        Route::get('system/logs/download', [SystemLogController::class, 'download'])->name('system-logs.download');
        Route::post('system/logs/clear', [SystemLogController::class, 'clear'])->name('system-logs.clear');

        // Template
        Route::get('notification-templates', [NotificationAdminController::class, 'templates'])->name('notification-templates.index');
        Route::get('notification-templates/create', [NotificationAdminController::class, 'createTemplate'])->name('notification-templates.create');
        Route::post('notification-templates', [NotificationAdminController::class, 'storeTemplate'])->name('notification-templates.store');
        Route::get('notification-templates/{template}/edit', [NotificationAdminController::class, 'editTemplate'])->name('notification-templates.edit');
        Route::put('notification-templates/{template}', [NotificationAdminController::class, 'updateTemplate'])->name('notification-templates.update');
        Route::delete('notification-templates/{template}', [NotificationAdminController::class, 'destroyTemplate'])->name('notification-templates.destroy');
        Route::post('notification-templates/{template}/test', [NotificationAdminController::class, 'testTemplate'])->name('notification-templates.test');

        // ===== ACTIVITY LOGS =====
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // ===== SCHEDULES / KALENDER JADWAL =====
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            Route::get('/data', [ScheduleController::class, 'data'])->name('data');
            Route::post('/', [ScheduleController::class, 'store'])->name('store');
            Route::put('/{schedule}', [ScheduleController::class, 'update'])->name('update');
            Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->name('destroy');
        });

        // ===== EXPORT ROUTES =====
        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('peserta/pdf', [ExportController::class, 'exportPesertaPdf'])->name('peserta.pdf');
            Route::get('peserta/excel', [ExportController::class, 'exportPesertaExcel'])->name('peserta.excel');
            Route::get('enrollments/pdf/{pelatihan?}', [ExportController::class, 'exportEnrollmentsPdf'])->name('enrollments.pdf');
            Route::get('enrollments/excel/{pelatihan?}', [ExportController::class, 'exportEnrollmentsExcel'])->name('enrollments.excel');
            Route::get('attendance/pdf/{pelatihan}', [ExportController::class, 'exportAttendancePdf'])->name('attendance.pdf');
            Route::get('attendance/excel/{pelatihan}', [ExportController::class, 'exportAttendanceExcel'])->name('attendance.excel');
            Route::get('certificates/pdf', [ExportController::class, 'exportCertificatesPdf'])->name('certificates.pdf');
            Route::get('certificates/excel', [ExportController::class, 'exportCertificatesExcel'])->name('certificates.excel');
        });

        // ===== FORM OPTIONS MANAGEMENT =====
        Route::get('form-options', [FormOptionController::class, 'index'])->name('form-options.index');
        Route::get('form-options/create', [FormOptionController::class, 'create'])->name('form-options.create');
        Route::post('form-options', [FormOptionController::class, 'store'])->name('form-options.store');
        Route::get('form-options/{masterOption}/edit', [FormOptionController::class, 'edit'])->name('form-options.edit');
        Route::put('form-options/{masterOption}', [FormOptionController::class, 'update'])->name('form-options.update');
        Route::delete('form-options/{masterOption}', [FormOptionController::class, 'destroy'])->name('form-options.destroy');
        Route::post('form-options/{masterOption}/toggle-active', [FormOptionController::class, 'toggleActive'])->name('form-options.toggle-active');
        Route::post('form-options/reorder', [FormOptionController::class, 'reorder'])->name('form-options.reorder');

        // ===== FORM FIELD CONFIG MANAGEMENT =====
        Route::get('form-config', [FormFieldConfigController::class, 'index'])->name('form-config.index');
        Route::post('form-config', [FormFieldConfigController::class, 'store'])->name('form-config.store');
        Route::get('form-config/{formFieldConfig}/edit', [FormFieldConfigController::class, 'edit'])->name('form-config.edit');
        Route::put('form-config/{formFieldConfig}', [FormFieldConfigController::class, 'update'])->name('form-config.update');
        Route::post('form-config/{formFieldConfig}/toggle-active', [FormFieldConfigController::class, 'toggleActive'])->name('form-config.toggle-active');
        Route::post('form-config/{formFieldConfig}/toggle-required', [FormFieldConfigController::class, 'toggleRequired'])->name('form-config.toggle-required');
        Route::post('form-config/reorder', [FormFieldConfigController::class, 'reorder'])->name('form-config.reorder');

        // ===== GOOGLE DRIVE =====
        Route::get('google-drive', [GoogleAuthController::class, 'statusPage'])->name('google-drive.status');
        Route::post('google-drive/revoke', [GoogleAuthController::class, 'revokeGoogleAccess'])->name('google-drive.revoke');
        Route::get('google-drive/status', [GoogleAuthController::class, 'checkGoogleStatus'])->name('google-drive.check-status');
        Route::get('google-drive/settings', [GoogleAuthController::class, 'edit'])->name('google-drive.settings');
        Route::put('google-drive/settings', [GoogleAuthController::class, 'update'])->name('google-drive.update');

        // ===== WHATSAPP SUPPORT NUMBERS =====
        Route::get('whatsapp-numbers', [WhatsAppSupportController::class, 'index'])->name('whatsapp-numbers.index');
        Route::post('whatsapp-numbers', [WhatsAppSupportController::class, 'store'])->name('whatsapp-numbers.store');
        Route::put('whatsapp-numbers/{id}', [WhatsAppSupportController::class, 'update'])->name('whatsapp-numbers.update');
        Route::delete('whatsapp-numbers/{id}', [WhatsAppSupportController::class, 'destroy'])->name('whatsapp-numbers.destroy');
        Route::post('whatsapp-numbers/reorder', [WhatsAppSupportController::class, 'reorder'])->name('whatsapp-numbers.reorder');
        Route::post('whatsapp-numbers/{id}/toggle-active', [WhatsAppSupportController::class, 'toggleActive'])->name('whatsapp-numbers.toggle-active');

        // ===== PUSH NOTIFICATIONS =====
        Route::get('push-notifications/estimate-count', [PushNotificationController::class, 'estimateCount'])
            ->name('push-notifications.estimate-count');
        Route::resource('push-notifications', PushNotificationController::class);
        Route::post('push-notifications/{notification}/send', [PushNotificationController::class, 'send'])
            ->name('push-notifications.send');
// ===== CLEAR CACHE =====
        Route::get('cache', [CacheController::class, 'index'])->name('cache.index');
        Route::post('cache/clear', [CacheController::class, 'clear'])->name('cache.clear');
    });
});

// ===== GOOGLE OAUTH ROUTES (public - for OAuth callback) =====
Route::prefix('auth/google')->name('google.')->group(function () {
    Route::get('redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('redirect');
    Route::get('callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('callback');
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

// ===== PWA: Halaman Offline =====
Route::get('/offline', function () {
    return view('pages.offline');
})->name('offline');

// ===== PUBLIC: Verifikasi Sertifikat =====
Route::get('/verifikasi-sertifikat', [CertificateController::class, 'verify'])->name('certificates.verify');

// ===== FOTO CAPTURE TEST (Livewire Component) =====
Route::get('/coba-foto-capture', function () {
    return view('content.peserta.coba-foto-capture');
})->name('coba.foto-capture');

// Old auth routes - redirect to new Jetstream routes
Route::get('/auth/login-basic', function () {
    return redirect('/login');
})->name('auth-login-basic');

Route::get('/auth/register-basic', function () {
    return redirect('/register');
})->name('auth-register-basic');
