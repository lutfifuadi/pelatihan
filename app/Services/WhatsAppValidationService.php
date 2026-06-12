<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppValidationService
{
    public function normalizeNumber(string $number): string
    {
        $number = ltrim($number, '+');

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (!str_starts_with($number, '62')) {
            return '62' . $number;
        }

        return $number;
    }

    public function isRegistered(string $number): bool
    {
        $number = $this->normalizeNumber($number);

        $checkUrl = Setting::where('key', 'whatsapp_check_url')->value('value')
            ?: config('whatsapp.check_url');
        $apiKey = Setting::where('key', 'whatsapp_api_key')->value('value')
            ?: config('whatsapp.api_key');
        $sender = Setting::where('key', 'whatsapp_sender')->value('value')
            ?: config('whatsapp.sender');

        if (!$checkUrl || !$apiKey) {
            Log::warning('WhatsAppValidationService: URL atau API Key tidak dikonfigurasi.');
            return false;
        }

        try {
            $response = Http::timeout(config('whatsapp.timeout'))
                ->asJson()
                ->post($checkUrl, [
                    'api_key' => $apiKey,
                    'sender'  => $sender,
                    'number'  => $number,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                return (bool) ($body['msg']['exists'] ?? false);
            }

            Log::warning('WhatsAppValidationService: Gagal cek nomor', [
                'number' => $number,
                'response' => $body ?? $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppValidationService: Error cek nomor {$number}: " . $e->getMessage());
            return false;
        }
    }

    public function bulkCheck(array $numbers): array
    {
        $results = [];

        foreach ($numbers as $number) {
            $normalized = $this->normalizeNumber($number);
            $results[$number] = [
                'normalized' => $normalized,
                'registered' => $this->isRegistered($normalized),
            ];
        }

        return $results;
    }
}
