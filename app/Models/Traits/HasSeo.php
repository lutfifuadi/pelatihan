<?php

namespace App\Models\Traits;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    /**
     * Setiap model yang pake trait ini punya satu SEO metadata.
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    /**
     * Inisialisasi SEO default untuk model ini.
     * Bisa di-override di masing-masing model.
     */
    public function initializeSeo(): array
    {
        return [
            'meta_title' => $this->seoTitle() ?? $this->getAttribute('name') ?? $this->getAttribute('nama') ?? 'Default Title',
            'meta_description' => $this->seoDescription() ?? $this->getAttribute('deskripsi') ?? $this->getAttribute('excerpt') ?? '',
            'meta_keywords' => $this->seoKeywords() ?? '',
            'og_type' => 'website',
            'robots' => 'index, follow',
            'sitemap_priority' => 0.8,
            'sitemap_changefreq' => 'weekly',
        ];
    }

    /**
     * Default auto-generate title — bisa di-override.
     */
    public function seoTitle(): ?string
    {
        return null; // override di model
    }

    public function seoDescription(): ?string
    {
        return null; // override di model
    }

    public function seoKeywords(): ?string
    {
        return null; // override di model
    }

    /**
     * Boot trait — auto-create SEO metadata saat model dibuat.
     */
    protected static function bootHasSeo(): void
    {
        static::created(function ($model) {
            $defaults = $model->initializeSeo();
            $model->seo()->create($defaults);
        });
    }
}
