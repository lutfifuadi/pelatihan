<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim pesan teks via WhatsApp.
     *
     * @param string $number Nomor tujuan (format: 628xx)
     * @param string $message Pesan yang akan dikirim
     * @return bool
     */
    public static function sendMessage(string $number, string $message): bool
    {
        $sendUrl = Setting::where('key', 'whatsapp_send_url')->value('value') ?: env('WA_SEND_URL');
        $apiKey  = Setting::where('key', 'whatsapp_api_key')->value('value') ?: env('WA_API_KEY');
        $sender  = Setting::where('key', 'whatsapp_sender')->value('value') ?: env('WA_SENDER');

        if (!$sendUrl || !$apiKey) {
            Log::warning('WhatsAppService: WA_SEND_URL atau WA_API_KEY tidak dikonfigurasi.');
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->asJson()
                ->post($sendUrl, [
                    'api_key' => $apiKey,
                    'sender'  => $sender,
                    'number'  => $number,
                    'message' => $message,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                Log::info("WhatsAppService: Pesan berhasil dikirim ke {$number}");
                return true;
            }

            Log::warning("WhatsAppService: Gagal kirim ke {$number}", [
                'response' => $body ?? $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error("WhatsAppService: Error kirim ke {$number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim password ke koordinator via WhatsApp.
     *
     * @param string $number Nomor tujuan
     * @param string $password Password yang dikirim
     * @param string $nama Nama koordinator
     * @return bool
     */
    public static function sendPassword(string $number, string $password, string $nama, string $nik = ''): bool
    {
        $message = "🎉 *Pendaftaran Koordinator Berhasil!*\n\n"
                 . "Halo *{$nama}*,\n\n"
                 . "Akun Koordinator Anda telah berhasil dibuat. Berikut adalah detail login Anda:\n\n"
                 . "🆔 *Username (NIK)*: `{$nik}`\n"
                 . "🔑 *Password*: `{$password}`\n\n"
                 . "⚠️ *Akun Anda masih menunggu persetujuan admin.*\n"
                 . "Silakan login setelah mendapatkan notifikasi aktivasi dari admin.\n\n"
                 . "Terima kasih.\n"
                 . "- " . (\App\Models\Setting::where('key', 'institution_name')->value('value') ?? 'MAN SABA');

        return self::sendMessage($number, $message);
    }
}
