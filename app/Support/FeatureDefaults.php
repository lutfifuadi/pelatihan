<?php

namespace App\Support;

class FeatureDefaults
{
    /**
     * Single Source of Truth (SOT) metadata untuk seluruh feature toggle di Aplikasi-Pelatihanku.
     */
    private static ?array $definitions = null;

    /**
     * Mengembalikan seluruh definisi fitur toggle.
     */
    public static function definitions(): array
    {
        if (self::$definitions !== null) {
            return self::$definitions;
        }

        self::$definitions = [
            // ── KELOMPOK 1: PENDAFTARAN & PUBLIK ──
            'fitur_pendaftaran_publik' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Pendaftaran & Publik',
                'label'       => 'Pendaftaran Publik (Landing Page)',
                'description' => 'Izinkan calon peserta baru melakukan pendaftaran mandiri langsung dari halaman Beranda / Landing Page.',
                'icon'        => 'tabler-user-plus',
                'badge_color' => '#3b82f6',
            ],
            'fitur_daftar_koordinator' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Pendaftaran & Publik',
                'label'       => 'Pendaftaran Mandiri Koordinator',
                'description' => 'Buka formulir pendaftaran publik bagi calon Koordinator Wilayah (/daftar-koordinator).',
                'icon'        => 'tabler-users',
                'badge_color' => '#6366f1',
            ],
            'fitur_faq_pengumuman' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Pendaftaran & Publik',
                'label'       => 'Widget FAQ & Pengumuman Publik',
                'description' => 'Tampilkan informasi pengumuman dan pertanyaan umum (FAQ) pada portal publik.',
                'icon'        => 'tabler-help-circle',
                'badge_color' => '#06b6d4',
            ],
            'fitur_cooldown_pendaftaran' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Pendaftaran & Publik',
                'label'       => 'Jeda Pendaftaran Ulang (Cooldown)',
                'description' => 'Terapkan aturan jeda waktu (cooldown) bagi peserta yang ingin mendaftar pelatihan baru atau setelah lulus.',
                'icon'        => 'tabler-hourglass-high',
                'badge_color' => '#f59e0b',
            ],

            // ── KELOMPOK 2: INTEGRASI & LAYANAN EKSTERNAL ──
            'fitur_verifikasi_kta' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Integrasi & Layanan',
                'label'       => 'Verifikasi Otomatis KTA Anggota',
                'description' => 'Aktifkan pencocokan nomor KTA anggota secara otomatis pada saat proses pendaftaran pelatihan.',
                'icon'        => 'tabler-id-badge-2',
                'badge_color' => '#10b981',
            ],
            'fitur_wa_gateway' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Integrasi & Layanan',
                'label'       => 'WhatsApp Gateway & Notifikasi',
                'description' => 'Kirimkan notifikasi WhatsApp otomatis untuk status pendaftaran, pengingat jadwal, dan broadcast.',
                'icon'        => 'tabler-brand-whatsapp',
                'badge_color' => '#22c55e',
            ],
            'fitur_sync_google_drive' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Integrasi & Layanan',
                'label'       => 'Sinkronisasi Otomatis Google Drive',
                'description' => 'Otomatis cadangkan berkas pendaftaran, dokumen, dan foto peserta ke folder Google Drive institusi.',
                'icon'        => 'tabler-brand-google-drive',
                'badge_color' => '#eab308',
            ],
            'fitur_push_notification' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Integrasi & Layanan',
                'label'       => 'Web Push Notifications',
                'description' => 'Kirim notifikasi web push langsung ke browser pengguna yang telah berlangganan.',
                'icon'        => 'tabler-bell-ringing',
                'badge_color' => '#f97316',
            ],

            // ── KELOMPOK 3: OPERASIONAL & SERTIFIKASI ──
            'fitur_absensi_qr' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Operasional & Sertifikasi',
                'label'       => 'Presensi Mandiri via QR Scanner',
                'description' => 'Aktifkan pemindaian QR code untuk pencatatan kehadiran instan pada sesi pelatihan.',
                'icon'        => 'tabler-qrcode',
                'badge_color' => '#8b5cf6',
            ],
            'fitur_sertifikat_online' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Operasional & Sertifikasi',
                'label'       => 'E-Sertifikat Digital & Unduhan',
                'description' => 'Terbitkan e-sertifikat ber-QR code dan izinkan peserta yang telah lulus mengunduh sertifikat.',
                'icon'        => 'tabler-certificate',
                'badge_color' => '#ec4899',
            ],
            'fitur_export_laporan' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Operasional & Sertifikasi',
                'label'       => 'Export Laporan (Excel / PDF)',
                'description' => 'Buka fungsionalitas ekspor data peserta, pendaftaran, dan sertifikat ke file Excel/PDF.',
                'icon'        => 'tabler-file-spreadsheet',
                'badge_color' => '#14b8a6',
            ],

            // ── KELOMPOK 4: SISTEM & ADMINISTRASI ──
            'fitur_impersonate_user' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Sistem & Administrasi',
                'label'       => 'Fitur Impersonate Pengguna',
                'description' => 'Izinkan Super Admin untuk login sementara sebagai user lain guna membantu troubleshooting.',
                'icon'        => 'tabler-user-share',
                'badge_color' => '#a855f7',
            ],
            'fitur_dynamic_form_config' => [
                'default'     => '1',
                'type'        => 'boolean',
                'group'       => 'fitur',
                'category'    => 'Sistem & Administrasi',
                'label'       => 'Kustomisasi Form Pendaftaran Dinamis',
                'description' => 'Aktifkan konfigurasi visibilitas dan mandatory field formulir pendaftaran peserta secara fleksibel.',
                'icon'        => 'tabler-input-search',
                'badge_color' => '#0ea5e9',
            ],
        ];

        return self::$definitions;
    }

    /**
     * Mengembalikan daftar key => default value.
     */
    public static function defaults(): array
    {
        $result = [];
        foreach (self::definitions() as $key => $meta) {
            $result[$key] = $meta['default'];
        }
        return $result;
    }

    /**
     * Mengembalikan metadata untuk key spesifik.
     */
    public static function get(string $key): ?array
    {
        $defs = self::definitions();
        if (!isset($defs[$key])) {
            return null;
        }

        $meta = $defs[$key];
        $meta['key'] = $key;
        return $meta;
    }

    /**
     * Cek apakah key terdaftar dalam SOT.
     */
    public static function has(string $key): bool
    {
        return isset(self::definitions()[$key]);
    }

    /**
     * Mengembalikan seluruh kategori unik.
     */
    public static function categories(): array
    {
        $categories = [];
        foreach (self::definitions() as $meta) {
            $cat = $meta['category'] ?? 'Umum';
            if (!in_array($cat, $categories, true)) {
                $categories[] = $cat;
            }
        }
        return $categories;
    }
}
