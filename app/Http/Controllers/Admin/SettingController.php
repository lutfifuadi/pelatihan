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
}
