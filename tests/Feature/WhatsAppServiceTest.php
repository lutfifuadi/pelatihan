<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Services\WhatsAppService;
use App\Services\WhatsAppValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    use RefreshDatabase;

    private WhatsAppValidationService $validationService;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->validationService = new WhatsAppValidationService();
    }

    public function test_validate_number_converts_08_format(): void
    {
        $result = WhatsAppService::validateNumber('08123456789');

        $this->assertEquals('628123456789', $result);
    }

    public function test_validate_number_keeps_62_format(): void
    {
        $result = WhatsAppService::validateNumber('6281234567890');

        $this->assertEquals('6281234567890', $result);
    }

    public function test_validate_number_adds_62_prefix(): void
    {
        $result = WhatsAppService::validateNumber('81234567890');

        $this->assertEquals('6281234567890', $result);
    }

    public function test_validate_number_strips_plus_prefix(): void
    {
        $result = WhatsAppService::validateNumber('+6281234567890');

        $this->assertEquals('6281234567890', $result);
    }

    public function test_validate_number_returns_null_for_empty(): void
    {
        $result = WhatsAppService::validateNumber('');

        $this->assertNull($result);
    }

    public function test_validate_number_returns_null_for_too_short(): void
    {
        $result = WhatsAppService::validateNumber('08123');

        $this->assertNull($result);
    }

    public function test_validate_number_trims_spaces(): void
    {
        $result = WhatsAppService::validateNumber('  08123456789  ');

        $this->assertEquals('628123456789', $result);
    }

    public function test_normalize_number_converts_08_format(): void
    {
        $result = $this->validationService->normalizeNumber('08123456789');

        $this->assertEquals('628123456789', $result);
    }

    public function test_normalize_number_keeps_62_format(): void
    {
        $result = $this->validationService->normalizeNumber('6281234567890');

        $this->assertEquals('6281234567890', $result);
    }

    public function test_normalize_number_strips_plus(): void
    {
        $result = $this->validationService->normalizeNumber('+6281234567890');

        $this->assertEquals('6281234567890', $result);
    }

    public function test_send_message_returns_false_when_not_configured(): void
    {
        Setting::where('key', 'whatsapp_send_url')->delete();

        $result = WhatsAppService::sendMessage('6281234567890', 'Test message');

        $this->assertFalse($result);
    }

    public function test_check_number_uses_http_and_returns_null_when_not_configured(): void
    {
        $result = WhatsAppService::checkNumber('6281234567890');

        $this->assertNull($result);
    }

    public function test_send_message_with_footer(): void
    {
        $result = WhatsAppService::sendMessageWithFooter('6281234567890', 'Test', 'Footer');

        $this->assertFalse($result);
    }

    public function test_reply_message_returns_false_when_not_configured(): void
    {
        $result = WhatsAppService::replyMessage('6281234567890', 'Reply', 'msgid123');

        $this->assertFalse($result);
    }

    public function test_bulk_check_returns_array(): void
    {
        $results = $this->validationService->bulkCheck(['08123456789', '6281234567890']);

        $this->assertCount(2, $results);
        $this->assertArrayHasKey('08123456789', $results);
        $this->assertArrayHasKey('6281234567890', $results);
        $this->assertEquals('628123456789', $results['08123456789']['normalized']);
        $this->assertEquals('6281234567890', $results['6281234567890']['normalized']);
    }

    public function test_check_device_status_returns_not_configured_when_empty(): void
    {
        Setting::whereIn('key', ['whatsapp_api_key', 'whatsapp_sender'])->delete();
        config(['whatsapp.api_key' => '']);
        config(['whatsapp.sender' => '']);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertFalse($result['connected']);
        $this->assertEquals('Not Configured', $result['status']);
    }

    public function test_check_device_status_returns_connected(): void
    {
        Setting::updateOrCreate(['key' => 'whatsapp_api_key'], ['value' => 'test-key', 'group' => 'whatsapp']);
        Setting::updateOrCreate(['key' => 'whatsapp_sender'], ['value' => '6281234567890', 'group' => 'whatsapp']);

        Http::fake([
            'wa.lutfifuadi.my.id/info-devices*' => Http::response([
                'status' => true,
                'info' => [
                    [
                        'status' => 'Connected'
                    ]
                ]
            ], 200)
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertTrue($result['connected']);
        $this->assertEquals('Connected', $result['status']);
    }

    public function test_check_device_status_returns_disconnected(): void
    {
        Setting::updateOrCreate(['key' => 'whatsapp_api_key'], ['value' => 'test-key', 'group' => 'whatsapp']);
        Setting::updateOrCreate(['key' => 'whatsapp_sender'], ['value' => '6281234567890', 'group' => 'whatsapp']);

        Http::fake([
            'wa.lutfifuadi.my.id/info-devices*' => Http::response([
                'status' => true,
                'info' => [
                    [
                        'status' => 'Disconnect'
                    ]
                ]
            ], 200)
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertFalse($result['connected']);
        $this->assertEquals('Disconnected', $result['status']);
    }

    public function test_check_device_status_handles_failure(): void
    {
        Setting::updateOrCreate(['key' => 'whatsapp_api_key'], ['value' => 'test-key', 'group' => 'whatsapp']);
        Setting::updateOrCreate(['key' => 'whatsapp_sender'], ['value' => '6281234567890', 'group' => 'whatsapp']);

        Http::fake([
            'wa.lutfifuadi.my.id/info-devices*' => Http::response([
                'status' => false,
                'message' => 'Invalid API key'
            ], 400)
        ]);

        $result = WhatsAppService::checkDeviceStatus();

        $this->assertFalse($result['connected']);
        $this->assertEquals('Error', $result['status']);
    }
}
