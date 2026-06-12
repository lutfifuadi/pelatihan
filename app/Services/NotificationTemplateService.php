<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use Illuminate\Database\Eloquent\Collection;

class NotificationTemplateService
{
    public function getByKey(string $key): ?NotificationTemplate
    {
        return NotificationTemplate::where('key', $key)
            ->where('is_active', true)
            ->first();
    }

    public function getAllActive(): Collection
    {
        return NotificationTemplate::where('is_active', true)->get();
    }

    public function render(string $key, array $data): string
    {
        $template = $this->getByKey($key);

        if (!$template) {
            return '';
        }

        $body = $template->body;

        foreach ($data as $placeholder => $value) {
            $body = str_replace('{' . $placeholder . '}', (string) $value, $body);
        }

        return $body;
    }
}
