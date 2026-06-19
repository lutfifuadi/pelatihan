<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFieldConfig extends Model
{
    protected $table = 'form_field_configs';

    protected $fillable = [
        'section',
        'field_key',
        'label',
        'placeholder',
        'type',
        'is_required',
        'is_active',
        'order',
        'width',
        'options_group',
        'validation_rules',
        'show_if',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'show_if' => 'array',
        ];
    }

    /**
     * Scope: filter by section, ordered by order, only active.
     */
    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section)
            ->where('is_active', true)
            ->orderBy('order');
    }
}
