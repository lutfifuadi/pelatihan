<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function branding()
    {
        $settings = Setting::whereIn('key', [
            'brand_name', 'brand_logo_size',
            'institution_name', 'institution_address', 'institution_phone', 'institution_email', 'institution_description',
            'footer_copyright', 'lock_kota', 'lock_provinsi'
        ])
            ->get()
            ->keyBy('key');

        return view('content.admin.branding.index', compact('settings'));
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
        ]);

        $keys = [
            'brand_name', 'brand_logo_size',
            'institution_name', 'institution_address', 'institution_phone', 'institution_email', 'institution_description',
            'footer_copyright', 'lock_kota', 'lock_provinsi'
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
