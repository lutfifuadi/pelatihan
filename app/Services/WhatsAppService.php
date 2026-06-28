<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected static function getConfig(string $key): string
    {
        $settingMap = [
            'send_url' => 'whatsapp_send_url',
            'check_url' => 'whatsapp_api_url', // database menggunakan whatsapp_api_url
            'api_key' => 'whatsapp_api_key',
            'sender' => 'whatsapp_sender',
            'check_api_key' => 'whatsapp_check_api_key',
            'check_sender' => 'whatsapp_check_sender',
        ];

        $settingKey = $settingMap[$key] ?? null;

        if ($settingKey) {
            $value = Setting::where('key', $settingKey)->value('value');
            if ($value) {
                return $value;
            }
        }

        return config("whatsapp.{$key}", '');
    }

    public static function sendMessage(string $number, string $message, ?string $footer = null): bool
    {
        $sendUrl = self::getConfig('send_url');
        $apiKey  = self::getConfig('api_key');
        $sender  = self::getConfig('sender');

        if (!$sendUrl || !$apiKey) {
            Log::warning('WhatsAppService: WA_SEND_URL atau WA_API_KEY tidak dikonfigurasi.');
            return false;
        }

        try {
            $payload = [
                'api_key' => $apiKey,
                'sender'  => $sender,
                'number'  => $number,
                'message' => $message,
            ];

            if ($footer !== null) {
                $payload['footer'] = $footer;
            }

            $response = Http::timeout((int) config('whatsapp.timeout', 15))
                ->asJson()
                ->post($sendUrl, $payload);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                Log::info("WhatsAppService: Pesan berhasil dikirim ke {$number}");
                return true;
            }

            Log::warning("WhatsAppService: Gagal kirim ke {$number}", [
                'response' => $body ?? $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: Error kirim ke {$number}: " . $e->getMessage());
            return false;
        }
    }

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
                  . "- " . (\App\Models\Setting::where('key', 'institution_name')->value('value') ?? 'Lembaga Pelatihan');

        return self::sendMessage($number, $message);
    }

    public static function sendMessageWithFooter(string $number, string $message, string $footer): bool
    {
        return self::sendMessage($number, $message, $footer);
    }

    public static function replyMessage(string $number, string $message, string $msgid): bool
    {
        $sendUrl = self::getConfig('send_url');
        $apiKey  = self::getConfig('api_key');
        $sender  = self::getConfig('sender');

        if (!$sendUrl || !$apiKey) {
            Log::warning('WhatsAppService: WA_SEND_URL atau WA_API_KEY tidak dikonfigurasi.');
            return false;
        }

        try {
            $response = Http::timeout((int) config('whatsapp.timeout', 15))
                ->asJson()
                ->post($sendUrl, [
                    'api_key' => $apiKey,
                    'sender'  => $sender,
                    'number'  => $number,
                    'message' => $message,
                    'msgid'   => $msgid,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                Log::info("WhatsAppService: Reply berhasil dikirim ke {$number}");
                return true;
            }

            Log::warning("WhatsAppService: Gagal reply ke {$number}", [
                'response' => $body ?? $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: Error reply ke {$number}: " . $e->getMessage());
            return false;
        }
    }

    public static function sendMessageFull(string $number, string $message): ?array
    {
        $sendUrl = self::getConfig('send_url');
        $apiKey  = self::getConfig('api_key');
        $sender  = self::getConfig('sender');

        if (!$sendUrl || !$apiKey) {
            Log::warning('WhatsAppService: WA_SEND_URL atau WA_API_KEY tidak dikonfigurasi.');
            return null;
        }

        try {
            $response = Http::timeout((int) config('whatsapp.timeout', 15))
                ->asJson()
                ->post($sendUrl, [
                    'api_key' => $apiKey,
                    'sender'  => $sender,
                    'number'  => $number,
                    'message' => $message,
                    'full'    => 1,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                Log::info("WhatsAppService: Pesan (full) berhasil dikirim ke {$number}");
                return $body['data'] ?? [];
            }

            Log::warning("WhatsAppService: Gagal kirim (full) ke {$number}", [
                'response' => $body ?? $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: Error kirim (full) ke {$number}: " . $e->getMessage());
            return null;
        }
    }

    public static function checkNumber(string $number): ?array
    {
        $checkUrl = self::getConfig('check_url');
        $apiKey   = self::getConfig('check_api_key') ?: self::getConfig('api_key');
        $sender   = self::getConfig('check_sender') ?: self::getConfig('sender');

        if (!$checkUrl || !$apiKey) {
            Log::warning('WhatsAppService: WA_CHECK_URL atau WA_API_KEY tidak dikonfigurasi.');
            return null;
        }

        try {
            $response = Http::timeout((int) config('whatsapp.timeout', 15))
                ->asJson()
                ->post($checkUrl, [
                    'api_key' => $apiKey,
                    'sender'  => $sender,
                    'number'  => $number,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                return [
                    'exists' => (bool) ($body['msg']['exists'] ?? false),
                    'jid'    => $body['msg']['jid'] ?? null,
                ];
            }

            Log::warning("WhatsAppService: Gagal cek nomor {$number}", [
                'response' => $body ?? $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: Error cek nomor {$number}: " . $e->getMessage());
            return null;
        }
    }

    public static function validateNumber(string $number): ?string
    {
        $number = trim($number);

        if (empty($number)) {
            return null;
        }

        $number = ltrim($number, '+');

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        if (strlen($number) < 10 || strlen($number) > 15) {
            Log::warning("WhatsAppService: Nomor {$number} tidak valid (panjang: " . strlen($number) . ")");
            return null;
        }

        return $number;
    }

    public static function sendMessageWithRetry(string $number, string $message, int $maxRetries = 3): bool
    {
        $attempts = 0;
        $delay = (int) config('whatsapp.retry.delay', 1000);

        while ($attempts < $maxRetries) {
            $attempts++;

            if (self::sendMessage($number, $message)) {
                return true;
            }

            Log::info("WhatsAppService: Percobaan {$attempts}/{$maxRetries} gagal untuk {$number}");

            if ($attempts < $maxRetries) {
                usleep($delay * 1000);
            }
        }

        Log::error("WhatsAppService: Semua {$maxRetries} percobaan gagal untuk {$number}");
        return false;
    }

    public function send(string $number, string $message, ?string $footer = null): bool
    {
        return self::sendMessage($number, $message, $footer);
    }

    public function check(string $number): ?array
    {
        return self::checkNumber($number);
    }

    public static function checkDeviceStatus(): array
    {
        $apiKey = self::getConfig('api_key');
        $sender = self::getConfig('sender');

        if (!$apiKey || !$sender) {
            return [
                'connected' => false,
                'status' => 'Not Configured',
                'message' => 'API Key atau Nomor Pengirim belum dikonfigurasi.'
            ];
        }

        try {
            $response = Http::timeout(10)->get('https://wa.lutfifuadi.my.id/info-devices', [
                'api_key' => $apiKey,
                'number'  => $sender,
            ]);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['status']) && $body['status'] === true) {
                    $info = $body['info'] ?? [];
                    if (!empty($info) && is_array($info)) {
                        $deviceInfo = $info[0] ?? [];
                        $status = $deviceInfo['status'] ?? '';
                        
                        if (strcasecmp($status, 'Connected') === 0) {
                            return [
                                'connected' => true,
                                'status' => 'Connected',
                                'message' => 'Perangkat terhubung.'
                            ];
                        } elseif (strcasecmp($status, 'Disconnect') === 0) {
                            return [
                                'connected' => false,
                                'status' => 'Disconnected',
                                'message' => 'Perangkat terputus.'
                            ];
                        } else {
                            return [
                                'connected' => false,
                                'status' => $status ?: 'Unknown',
                                'message' => 'Status perangkat: ' . ($status ?: 'Tidak diketahui')
                            ];
                        }
                    }
                }
                
                $message = $body['message'] ?? 'Respon API tidak sesuai format.';
                return [
                    'connected' => false,
                    'status' => 'Error',
                    'message' => $message
                ];
            }

            return [
                'connected' => false,
                'status' => 'Error',
                'message' => 'Koneksi ke gateway gagal dengan status ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('WhatsAppService checkDeviceStatus error: ' . $e->getMessage());
            return [
                'connected' => false,
                'status' => 'Offline',
                'message' => 'Tidak dapat terhubung ke server gateway: ' . $e->getMessage()
            ];
        }
    }

    public static function checkCheckDeviceStatus(): array
    {
        $apiKey = self::getConfig('check_api_key') ?: self::getConfig('api_key');
        $sender = self::getConfig('check_sender') ?: self::getConfig('sender');

        if (!$apiKey || !$sender) {
            return [
                'connected' => false,
                'status' => 'Not Configured',
                'message' => 'API Key atau Nomor Pengirim Cek Nomor belum dikonfigurasi.'
            ];
        }

        try {
            $response = Http::timeout(10)->get('https://wa.lutfifuadi.my.id/info-devices', [
                'api_key' => $apiKey,
                'number'  => $sender,
            ]);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['status']) && $body['status'] === true) {
                    $info = $body['info'] ?? [];
                    if (!empty($info) && is_array($info)) {
                        $deviceInfo = $info[0] ?? [];
                        $status = $deviceInfo['status'] ?? '';
                        
                        if (strcasecmp($status, 'Connected') === 0) {
                            return [
                                'connected' => true,
                                'status' => 'Connected',
                                'message' => 'Perangkat terhubung.'
                            ];
                        } elseif (strcasecmp($status, 'Disconnect') === 0) {
                            return [
                                'connected' => false,
                                'status' => 'Disconnected',
                                'message' => 'Perangkat terputus.'
                            ];
                        } else {
                            return [
                                'connected' => false,
                                'status' => $status ?: 'Unknown',
                                'message' => 'Status perangkat: ' . ($status ?: 'Tidak diketahui')
                            ];
                        }
                    }
                }
                
                $message = $body['message'] ?? 'Respon API tidak sesuai format.';
                return [
                    'connected' => false,
                    'status' => 'Error',
                    'message' => $message
                ];
            }

            return [
                'connected' => false,
                'status' => 'Error',
                'message' => 'Koneksi ke gateway gagal dengan status ' . $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('WhatsAppService checkCheckDeviceStatus error: ' . $e->getMessage());
            return [
                'connected' => false,
                'status' => 'Offline',
                'message' => 'Tidak dapat terhubung ke server gateway: ' . $e->getMessage()
            ];
        }
    }
}
