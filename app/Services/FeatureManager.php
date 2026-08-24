<?php

namespace App\Services;

use App\Exceptions\FeatureDisabledException;
use App\Support\FeatureDefaults;

class FeatureManager
{
    protected SettingsManager $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Cek apakah fitur dalam kondisi AKTIF (ON).
     */
    public function isOn(string $key): bool
    {
        return $this->settings->getBool($key);
    }

    /**
     * Cek apakah fitur dalam kondisi NON-AKTIF (OFF).
     */
    public function isOff(string $key): bool
    {
        return !$this->isOn($key);
    }

    /**
     * Guard: Lempar exception jika fitur NON-AKTIF.
     *
     * @throws FeatureDisabledException
     */
    public function guard(string $key): void
    {
        if ($this->isOff($key)) {
            $meta = FeatureDefaults::get($key);
            $label = $meta['label'] ?? $key;
            throw new FeatureDisabledException("Fitur \"{$label}\" sedang dinonaktifkan oleh administrator.");
        }
    }

    /**
     * Mengembalikan seluruh daftar feature toggle beserta status aktif/non-aktif saat ini.
     */
    public function list(): array
    {
        $definitions = FeatureDefaults::definitions();
        $result = [];

        foreach ($definitions as $key => $meta) {
            $meta['key'] = $key;
            $meta['is_on'] = $this->isOn($key);
            $result[$key] = $meta;
        }

        return $result;
    }

    /**
     * Mengembalikan daftar fitur yang telah dikelompokkan berdasarkan kategori.
     */
    public function listGrouped(): array
    {
        $features = $this->list();
        $grouped = [];

        foreach ($features as $key => $meta) {
            $category = $meta['category'] ?? 'Umum';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][$key] = $meta;
        }

        return $grouped;
    }

    /**
     * Ubah status satu fitur.
     */
    public function set(string $key, bool $value): void
    {
        $meta = FeatureDefaults::get($key);
        $this->settings->setBool($key, $value, 'fitur', $meta['label'] ?? null);
    }
}
