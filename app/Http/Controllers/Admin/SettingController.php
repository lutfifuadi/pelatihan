<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WhatsappNumber;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function branding()
    {
        $settings = Setting::whereIn('key', [
            'brand_name', 'brand_logo_size',
            'institution_name', 'institution_address', 'institution_phone', 'institution_email', 'institution_description',
            'footer_copyright', 'lock_kota', 'lock_provinsi', 'validate_whatsapp', 'broadcast_enabled', 'timezone'
        ])
            ->get()
            ->keyBy('key');

        $whatsappNumbers = WhatsappNumber::sorted()->get();
        return view('content.admin.branding.index', compact('settings', 'whatsappNumbers'));
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:100',
            'brand_logo_size' => 'required|in:sm,md,lg,xl',
            'institution_name' => 'required|string|max:200',
            'institution_address' => 'nullable|string|max:500',
            'institution_phone' => 'nullable|string|max:50',
            'institution_email' => 'nullable|email|max:200',
            'institution_description' => 'nullable|string|max:1000',
            'footer_copyright' => 'nullable|string|max:200',
            'lock_kota' => 'nullable|string|max:100',
            'lock_provinsi' => 'nullable|string|max:100',
            'validate_whatsapp' => 'required|in:0,1',
            'broadcast_enabled' => 'required|in:0,1',
            'timezone' => 'required|string|max:100',
        ]);

        $keys = [
            'brand_name', 'brand_logo_size',
            'institution_name', 'institution_address', 'institution_phone', 'institution_email', 'institution_description',
            'footer_copyright', 'lock_kota', 'lock_provinsi', 'validate_whatsapp', 'broadcast_enabled', 'timezone'
        ];
        $labels = [
            'brand_name' => 'Nama Brand Aplikasi',
            'brand_logo_size' => 'Ukuran Logo',
            'institution_name' => 'Nama Institusi/Lembaga',
            'institution_address' => 'Alamat Institusi',
            'institution_phone' => 'Nomor Telepon',
            'institution_email' => 'Email Institusi',
            'institution_description' => 'Deskripsi Institusi',
            'footer_copyright' => 'Footer Copyright',
            'lock_kota' => 'Kota/Kabupaten (terkunci)',
            'lock_provinsi' => 'Provinsi (terkunci)',
            'validate_whatsapp' => 'Validasi Otomatis Nomor WhatsApp',
            'broadcast_enabled' => 'Aktifkan Broadcast Real-time',
            'timezone' => 'Zona Waktu Aplikasi',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $request->input($key),
                    'group' => 'general',
                    'label' => $labels[$key] ?? '',
                ]
            );
        }

        return redirect()->route('admin.settings.branding')
            ->with('success', 'Pengaturan branding berhasil diperbarui.');
    }

    public function landing()
    {
        $settings = Setting::where('group', 'landing')
            ->get()
            ->keyBy('key');

        return view('content.admin.branding.landing', compact('settings'));
    }

    public function updateLanding(Request $request)
    {
        $rules = [];
        $keys = [
            'hero_title', 'hero_subtitle', 'hero_description',
            'hero_stat_1_value', 'hero_stat_1_label',
            'hero_stat_2_value', 'hero_stat_2_label',
            'hero_stat_3_value', 'hero_stat_3_label',
            'hero_scroll_text',
            'hero_tag_1_icon', 'hero_tag_1_text',
            'hero_tag_2_icon', 'hero_tag_2_text',
            'hero_tag_3_icon', 'hero_tag_3_text',
            'form_title', 'form_password_info', 'form_password_value',
            'form_button_text', 'form_button_loading',
            'form_login_text', 'form_login_link',
            'steps_badge', 'steps_title', 'steps_subtitle',
            'steps_1_title', 'steps_1_desc',
            'steps_2_title', 'steps_2_desc',
            'steps_3_title', 'steps_3_desc',
            'pelatihan_badge', 'pelatihan_title', 'pelatihan_subtitle',
            'pelatihan_empty_title', 'pelatihan_empty_desc',
            'why_badge', 'why_title', 'why_subtitle',
            'cta_badge', 'cta_title', 'cta_subtitle',
            'cta_button_text', 'cta_login_text',
        ];

        $labels = [
            'hero_title' => 'Hero: Judul Utama',
            'hero_subtitle' => 'Hero: Subjudul',
            'hero_description' => 'Hero: Deskripsi',
            'hero_stat_1_value' => 'Hero: Statistik 1 - Angka',
            'hero_stat_1_label' => 'Hero: Statistik 1 - Label',
            'hero_stat_2_value' => 'Hero: Statistik 2 - Angka',
            'hero_stat_2_label' => 'Hero: Statistik 2 - Label',
            'hero_stat_3_value' => 'Hero: Statistik 3 - Angka',
            'hero_stat_3_label' => 'Hero: Statistik 3 - Label',
            'hero_scroll_text' => 'Hero: Teks Scroll Info',
            'hero_tag_1_icon' => 'Hero: Tag 1 - Icon',
            'hero_tag_1_text' => 'Hero: Tag 1 - Teks',
            'hero_tag_2_icon' => 'Hero: Tag 2 - Icon',
            'hero_tag_2_text' => 'Hero: Tag 2 - Teks',
            'hero_tag_3_icon' => 'Hero: Tag 3 - Icon',
            'hero_tag_3_text' => 'Hero: Tag 3 - Teks',
            'form_title' => 'Form: Judul',
            'form_password_info' => 'Form: Info Password',
            'form_password_value' => 'Form: Default Password',
            'form_button_text' => 'Form: Tombol',
            'form_button_loading' => 'Form: Loading State',
            'form_login_text' => 'Form: Teks Login',
            'form_login_link' => 'Form: Link Login',
            'steps_badge' => 'Langkah: Badge',
            'steps_title' => 'Langkah: Judul',
            'steps_subtitle' => 'Langkah: Subjudul',
            'steps_1_title' => 'Langkah 1: Judul',
            'steps_1_desc' => 'Langkah 1: Deskripsi',
            'steps_2_title' => 'Langkah 2: Judul',
            'steps_2_desc' => 'Langkah 2: Deskripsi',
            'steps_3_title' => 'Langkah 3: Judul',
            'steps_3_desc' => 'Langkah 3: Deskripsi',
            'pelatihan_badge' => 'Pelatihan: Badge',
            'pelatihan_title' => 'Pelatihan: Judul',
            'pelatihan_subtitle' => 'Pelatihan: Subjudul',
            'pelatihan_empty_title' => 'Pelatihan: Empty Title',
            'pelatihan_empty_desc' => 'Pelatihan: Empty Desc',
            'why_badge' => 'Mengapa: Badge',
            'why_title' => 'Mengapa: Judul',
            'why_subtitle' => 'Mengapa: Subjudul',
            'cta_badge' => 'CTA: Badge',
            'cta_title' => 'CTA: Judul',
            'cta_subtitle' => 'CTA: Subjudul',
            'cta_button_text' => 'CTA: Tombol',
            'cta_login_text' => 'CTA: Tombol Login',
        ];

        foreach ($keys as $key) {
            $rules[$key] = 'nullable|string|max:500';
        }

        $request->validate($rules);

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $request->input($key, ''),
                    'group' => 'landing',
                    'label' => $labels[$key] ?? '',
                ]
            );
        }

        return redirect()->route('admin.settings.landing')
            ->with('success', 'Konten halaman publik berhasil diperbarui.');
    }

    public function seo()
    {
        $settings = Setting::whereIn('key', [
            'seo_default_title', 'seo_default_description', 'seo_default_keywords',
            'seo_og_image', 'seo_twitter_handle', 'seo_facebook_page',
            'seo_instagram_handle', 'seo_org_name', 'seo_org_logo',
        ])
            ->get()
            ->keyBy('key');

        return view('content.admin.seo.index', compact('settings'));
    }

    public function updateSeo(Request $request)
    {
        $request->validate([
            'seo_default_title' => 'required|string|max:100',
            'seo_default_description' => 'required|string|max:200',
            'seo_default_keywords' => 'nullable|string|max:255',
            'seo_og_image' => 'nullable|string|max:255',
            'seo_twitter_handle' => 'nullable|string|max:50',
            'seo_facebook_page' => 'nullable|string|max:255',
            'seo_instagram_handle' => 'nullable|string|max:50',
            'seo_org_name' => 'required|string|max:100',
            'seo_org_logo' => 'nullable|string|max:255',
        ]);

        $keys = [
            'seo_default_title', 'seo_default_description', 'seo_default_keywords',
            'seo_og_image', 'seo_twitter_handle', 'seo_facebook_page',
            'seo_instagram_handle', 'seo_org_name', 'seo_org_logo',
        ];

        $labels = [
            'seo_default_title' => 'Default Title',
            'seo_default_description' => 'Default Description',
            'seo_default_keywords' => 'Default Keywords',
            'seo_og_image' => 'OG Image Path',
            'seo_twitter_handle' => 'Twitter Handle',
            'seo_facebook_page' => 'Facebook Page',
            'seo_instagram_handle' => 'Instagram Handle',
            'seo_org_name' => 'Organization Name (Schema)',
            'seo_org_logo' => 'Organization Logo (Schema)',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $request->input($key),
                    'group' => 'seo',
                    'label' => $labels[$key] ?? '',
                ]
            );
        }

        return redirect()->route('admin.settings.seo')
            ->with('success', 'Pengaturan SEO berhasil diperbarui.');
    }
}
