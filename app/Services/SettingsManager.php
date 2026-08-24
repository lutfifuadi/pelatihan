<?php

namespace App\Services;

use App\Models\Setting;
use App\Support\FeatureDefaults;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsManager
{
    public const CACHE_KEY_ALL = 'setting.all';
    public const CACHE_TTL_SECONDS = 3600;

    /**
     * Ambil seluruh data pengaturan (dari cache atau DB).
     */
    public function all(?string $group = null): array
    {
        try {
            $all = Cache::remember(self::CACHE_KEY_ALL, self::CACHE_TTL_SECONDS, function () {
                $rows = Setting::select('key', 'value', 'group', 'label')->get();
                $data = [];
                foreach ($rows as $row) {
                    $data[$row->key] = $row->value;
                }
                return $data;
            });
        } catch (\Throwable $e) {
            Log::warning('SettingsManager: Gagal membaca cache, fallback ke DB: ' . $e->getMessage());
            $rows = Setting::select('key', 'value')->get();
            $all = [];
            foreach ($rows as $row) {
                $all[$row->key] = $row->value;
            }
        }

        if ($group === null) {
            return $all;
        }

        $filtered = [];
        $rows = Setting::where('group', $group)->get();
        foreach ($rows as $row) {
            $filtered[$row->key] = $row->value;
        }
        return $filtered;
    }

    /**
     * Baca nilai pengaturan berdasarkan key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        if (array_key_exists($key, $all)) {
            $val = $all[$key];
            if ($val !== null) {
                return $val;
            }
        }

        if ($default !== null) {
            return $default;
        }

        // Cek fallback ke FeatureDefaults
        $meta = FeatureDefaults::get($key);
        return $meta !== null ? $meta['default'] : null;
    }

    /**
     * Baca nilai string.
     */
    public function getString(string $key, ?string $default = null): ?string
    {
        $val = $this->get($key, $default);
        return $val !== null ? (string) $val : null;
    }

    /**
     * Baca nilai boolean.
     */
    public function getBool(string $key, bool $default = false): bool
    {
        $val = $this->get($key);

        if ($val === null) {
            $meta = FeatureDefaults::get($key);
            $val = $meta !== null ? $meta['default'] : ($default ? '1' : '0');
        }

        if (is_bool($val)) {
            return $val;
        }

        $str = strtolower(trim((string) $val));
        return in_array($str, ['1', 'true', 'ya', 'yes', 'on'], true);
    }

    /**
     * Baca nilai integer.
     */
    public function getInt(string $key, int $default = 0): int
    {
        $val = $this->get($key, $default);
        return (int) $val;
    }

    /**
     * Cek apakah key ada di database atau FeatureDefaults.
     */
    public function has(string $key): bool
    {
        $all = $this->all();
        if (array_key_exists($key, $all)) {
            return true;
        }
        return FeatureDefaults::has($key);
    }

    /**
     * Simpan nilai pengaturan tunggal.
     */
    public function set(string $key, mixed $value, ?string $group = null, ?string $label = null): void
    {
        $meta = FeatureDefaults::get($key);
        $finalGroup = $group ?? ($meta['group'] ?? 'general');
        $finalLabel = $label ?? ($meta['label'] ?? null);

        if ($meta && ($meta['type'] ?? '') === 'boolean') {
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            } elseif (in_array(strtolower(trim((string) $value)), ['1', 'true', 'ya', 'on'], true)) {
                $value = '1';
            } else {
                $value = '0';
            }
        }

        DB::transaction(function () use ($key, $value, $finalGroup, $finalLabel) {
            $attributes = ['key' => $key];
            $values = [
                'value' => (string) $value,
                'group' => $finalGroup,
            ];

            if ($finalLabel !== null) {
                $values['label'] = $finalLabel;
            }

            Setting::updateOrCreate($attributes, $values);
            $this->forget($key);
        });
    }

    /**
     * Simpan nilai boolean.
     */
    public function setBool(string $key, bool $value, ?string $group = 'fitur', ?string $label = null): void
    {
        $this->set($key, $value ? '1' : '0', $group, $label);
    }

    /**
     * Batch update dalam satu transaksi.
     */
    public function setMany(array $values, ?string $group = null): void
    {
        DB::transaction(function () use ($values, $group) {
            foreach ($values as $key => $val) {
                $meta = FeatureDefaults::get($key);
                $finalGroup = $group ?? ($meta['group'] ?? 'general');
                $finalLabel = $meta['label'] ?? null;

                if ($meta && ($meta['type'] ?? '') === 'boolean') {
                    if (is_bool($val)) {
                        $val = $val ? '1' : '0';
                    } elseif (in_array(strtolower(trim((string) $val)), ['1', 'true', 'ya', 'on'], true)) {
                        $val = '1';
                    } else {
                        $val = '0';
                    }
                }

                $updateData = [
                    'value' => (string) $val,
                    'group' => $finalGroup,
                ];
                if ($finalLabel !== null) {
                    $updateData['label'] = $finalLabel;
                }

                Setting::updateOrCreate(['key' => $key], $updateData);
            }

            $this->flush();
        });
    }

    /**
     * Hapus cache key spesifik & cache agregat.
     */
    public function forget(string $key): void
    {
        try {
            Cache::forget(self::CACHE_KEY_ALL);
            Cache::forget("setting.{$key}");
            Cache::forget('setting.general');
            Cache::forget('setting.landing');
            Cache::forget('setting.seo');
        } catch (\Throwable $e) {
            Log::warning("SettingsManager: Invalidation cache gagal untuk {$key}: " . $e->getMessage());
        }
    }

    /**
     * Flush seluruh cache pengaturan.
     */
    public function flush(): void
    {
        try {
            Cache::forget(self::CACHE_KEY_ALL);
            Cache::forget('setting.general');
            Cache::forget('setting.landing');
            Cache::forget('setting.seo');
            Cache::forget('setting.maintenance_mode');

            foreach (array_keys(FeatureDefaults::definitions()) as $key) {
                Cache::forget("setting.{$key}");
            }
        } catch (\Throwable $e) {
            Log::warning('SettingsManager: Flush cache gagal: ' . $e->getMessage());
        }
    }
}
