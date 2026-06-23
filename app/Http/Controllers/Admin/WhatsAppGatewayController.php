<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class WhatsAppGatewayController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'whatsapp')->get()->keyBy('key');
        return view('content.admin.whatsapp-gateway.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_api_key' => 'nullable|string',
            'whatsapp_send_url' => 'nullable|string',
            'whatsapp_api_url' => 'nullable|string',
            'whatsapp_sender' => 'nullable|string',
            'whatsapp_check_api_key' => 'nullable|string',
            'whatsapp_check_sender' => 'nullable|string',
        ]);

        $keys = [
            'whatsapp_api_key',
            'whatsapp_send_url',
            'whatsapp_api_url',
            'whatsapp_sender',
            'whatsapp_check_api_key',
            'whatsapp_check_sender',
        ];
        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key), 'group' => 'whatsapp']
            );
        }

        return redirect()->route('admin.whatsapp-gateway.index')
            ->with('success', 'Pengaturan WhatsApp berhasil diperbarui.');
    }

    public function test()
    {
        return redirect()->route('admin.whatsapp-gateway.index')
            ->with('info', 'Fitur test kirim akan segera tersedia.');
    }
}
