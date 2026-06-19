<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterOption extends Model
{
    protected $table = 'master_options';

    protected $fillable = [
        'group_key',
        'label',
        'value',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: filter by group_key, ordered by order, only active.
     */
    public function scopeByGroup($query, string $groupKey)
    {
        return $query->where('group_key', $groupKey)
            ->where('is_active', true)
            ->orderBy('order');
    }
}
