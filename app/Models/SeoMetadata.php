<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $table = 'seo_metadata';

    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'robots',
        'sitemap_priority',
        'sitemap_changefreq',
        'structured_data',
    ];

    protected $casts = [
        'structured_data' => 'array',
        'sitemap_priority' => 'decimal:1',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
