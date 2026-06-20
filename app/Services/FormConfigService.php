<?php

namespace App\Services;

use App\Models\FormFieldConfig;
use App\Models\MasterOption;
use Illuminate\Support\Collection;

class FormConfigService
{
    /**
     * Ambil semua field config untuk section tertentu (hanya yang aktif).
     */
    public function getFieldsBySection(string $section): Collection
    {
        return FormFieldConfig::bySection($section)->get();
    }

    /**
     * Ambil semua field yang aktif per section (untuk render form lengkap).
     *
     * @return array<string, Collection> Key adalah nama section, value adalah koleksi field.
     */
    public function getAllSections(): array
    {
        $sections = ['data_pribadi', 'alamat_kontak', 'pendidikan', 'minat', 'dokumen'];
        $result = [];

        foreach ($sections as $section) {
            $result[$section] = $this->getFieldsBySection($section);
        }

        return $result;
    }

    /**
     * Ambil opsi dropdown berdasarkan group_key.
     * Return collection dengan key = value, value = label.
     */
    public function getOptions(string $groupKey): Collection
    {
        return MasterOption::byGroup($groupKey)->pluck('label', 'value');
    }

    /**
     * Build validation rules array dari konfigurasi (untuk dipakai di controller).
     *
     * @return array<string, string[]> Array of rules dengan key = field_key.
     */
    public function buildValidationRules(string $section): array
    {
        $fields = $this->getFieldsBySection($section);
        $rules = [];

        foreach ($fields as $field) {
            $rule = [];

            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            // Map tipe ke aturan dasar
            switch ($field->type) {
                case 'email':
                    $rule[] = 'email';
                    break;
                case 'tel':
                    $rule[] = 'string';
                    break;
                case 'file':
                    $rule[] = 'file';
                    break;
                case 'number':
                    $rule[] = 'numeric';
                    break;
                default:
                    $rule[] = 'string';
            }

            if ($field->validation_rules) {
                // Merge custom rules dari konfigurasi
                $customRules = explode('|', $field->validation_rules);
                // Filter: uppercase & readonly bukan rule validasi backend
                // (uppercase sudah ditangani JS frontend, readonly hanya hint display)
                $customRules = array_filter($customRules, fn($r) => !in_array($r, ['uppercase', 'readonly']));
                $rule = array_merge($rule, array_values($customRules));
            }

            $rules[$field->field_key] = $rule;
        }

        return $rules;
    }

    /**
     * Cek apakah field tertentu required berdasarkan konfigurasi.
     */
    public function isFieldRequired(string $section, string $fieldKey): bool
    {
        $field = FormFieldConfig::where('section', $section)
            ->where('field_key', $fieldKey)
            ->first();

        return $field ? $field->is_required : false;
    }
}
