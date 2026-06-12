<?php

namespace App\Services;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SEOManager
{
    private array $data = [];
    private array $jsonLd = [];
    private ?Model $model = null;
    private ?SeoMetadata $seoMeta = null;

    /**
     * Set SEO dari model (via polymorphic SeoMetadata).
     */
    public function fromModel(Model $model): self
    {
        $this->model = $model;

        // Load SEO metadata dari relasi polymorphic
        $this->seoMeta = $model->seo;

        if ($this->seoMeta) {
            $this->data = [
                'title' => $this->seoMeta->meta_title,
                'description' => $this->seoMeta->meta_description,
                'keywords' => $this->seoMeta->meta_keywords,
                'og_title' => $this->seoMeta->og_title,
                'og_description' => $this->seoMeta->og_description,
                'og_image' => $this->seoMeta->og_image,
                'og_type' => $this->seoMeta->og_type ?? 'website',
                'twitter_title' => $this->seoMeta->twitter_title,
                'twitter_description' => $this->seoMeta->twitter_description,
                'twitter_image' => $this->seoMeta->twitter_image,
                'canonical' => $this->seoMeta->canonical_url ?? request()->url(),
                'robots' => $this->seoMeta->robots ?? 'index, follow',
                'sitemap_priority' => $this->seoMeta->sitemap_priority,
                'sitemap_changefreq' => $this->seoMeta->sitemap_changefreq,
            ];
        }

        return $this;
    }

    /**
     * Set SEO manual (untuk halaman statis).
     */
    public function set(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * Set title dengan auto-suffix.
     */
    public function title(string $title): self
    {
        $this->data['title'] = $title;
        return $this;
    }

    /**
     * Set description.
     */
    public function description(string $description): self
    {
        $this->data['description'] = $description;
        return $this;
    }

    /**
     * Setup SEO untuk halaman statis (Home, About, Contact, FAQ, dll).
     * Konfigurasi diambil dari config/seo.php → static_pages.
     */
    public function staticPage(string $pageKey): self
    {
        $pages = config('seo.static_pages', []);

        if (isset($pages[$pageKey])) {
            $this->data = array_merge($this->data, $pages[$pageKey]);
        }

        return $this;
    }

    /**
     * Set Open Graph image.
     */
    public function ogImage(string $url): self
    {
        $this->data['og_image'] = $url;
        return $this;
    }

    /**
     * Set canonical URL.
     */
    public function canonical(string $url): self
    {
        $this->data['canonical'] = $url;
        return $this;
    }

    /**
     * Set noindex (untuk halaman yang tidak mau di-index).
     */
    public function noindex(bool $value = true): self
    {
        $this->data['robots'] = $value ? 'noindex, nofollow' : 'index, follow';
        return $this;
    }

    /**
     * Tambahkan JSON-LD structured data.
     */
    public function addJsonLd(array $schema): self
    {
        $this->jsonLd[] = $schema;
        return $this;
    }

    // ========== SCHEMA GENERATORS ==========

    /**
     * Generate Organization schema.
     */
    public function organizationSchema(): array
    {
        $org = config('seo.schema.organization');
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $org['name'],
            'url' => $org['url'],
            'logo' => url($org['logo']),
        ];
    }

    /**
     * Generate WebSite schema.
     */
    public function websiteSchema(): array
    {
        $ws = config('seo.schema.website');
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $ws['name'],
            'url' => $ws['url'],
        ];
    }

    /**
     * Generate BreadcrumbList schema.
     */
    public function breadcrumbSchema(array $crumbs): array
    {
        $items = [];
        $position = 1;
        foreach ($crumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $crumb['label'],
                'item' => $crumb['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Generate FAQPage schema untuk halaman FAQ.
     */
    public function faqPageSchema($faqs): array
    {
        $items = [];
        foreach ($faqs as $faq) {
            $items[] = [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq->answer),
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ];
    }

    /**
     * Generate Course schema untuk model Pelatihan — sesuai Google 2026.
     */
    public function courseSchema(?Model $training = null): ?array
    {
        $model = $training ?? $this->model;
        if (!$model) return null;

        $orgName = config('seo.schema.organization.name');
        $orgUrl = config('seo.schema.organization.url');
        $socials = config('seo.social', []);

        $sameAs = [$orgUrl];
        if (!empty($socials['facebook_page'])) $sameAs[] = $socials['facebook_page'];
        if (!empty($socials['instagram_handle'])) $sameAs[] = 'https://instagram.com/' . $socials['instagram_handle'];
        if (!empty($socials['twitter_handle'])) $sameAs[] = 'https://twitter.com/' . $socials['twitter_handle'];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $model->nama ?? $model->name ?? '',
            'description' => Str::limit($model->deskripsi ?? '', 200),
            'provider' => [
                '@type' => 'Organization',
                'name' => $orgName,
                'sameAs' => $sameAs,
            ],
        ];

        if ($model->exists) {
            $schemaKey = $model->slug ?? $model->id;
            $schema['url'] = url('/pelatihan/' . $schemaKey);
        }

        // Image (recommended by Google)
        if (!empty($model->gambar) || !empty($model->image) || !empty($model->thumbnail)) {
            $schema['image'] = url($model->gambar ?? $model->image ?? $model->thumbnail);
        }

        // CourseInstance jika ada jadwal
        if (!empty($model->tanggal_mulai)) {
            $instance = [
                '@type' => 'CourseInstance',
                'courseMode' => $model->course_mode ?? 'Online',
                'courseSchedule' => [
                    '@type' => 'Schedule',
                    'startDate' => $model->tanggal_mulai->toIso8601String(),
                ],
            ];

            if (!empty($model->tanggal_selesai)) {
                $instance['courseSchedule']['endDate'] = $model->tanggal_selesai->toIso8601String();
            }

            // Offers — jika ada biaya
            if (isset($model->biaya) || isset($model->harga) || isset($model->price)) {
                $price = $model->biaya ?? $model->harga ?? $model->price;
                $instance['offers'] = [
                    '@type' => 'Offer',
                    'price' => (float) $price,
                    'priceCurrency' => $model->mata_uang ?? 'IDR',
                    'availability' => 'https://schema.org/InStock',
                    'validFrom' => $model->created_at?->toIso8601String(),
                ];
            }

            $schema['hasCourseInstance'] = $instance;
        }

        return $schema;
    }

    // ========== RENDER OUTPUT ==========

    /**
     * Render semua meta tags sebagai string HTML siap pakai.
     */
    public function render(): string
    {
        $html = '';

        // Title
        $title = $this->getTitle();
        $html .= "    <title>" . e($title) . "</title>\n";

        // Meta description
        $desc = $this->get('description', config('seo.defaults.description'));
        $html .= '    <meta name="description" content="' . e($desc) . "\">\n";

        // Meta keywords
        $keywords = $this->get('keywords', config('seo.defaults.keywords'));
        if ($keywords) {
            $html .= '    <meta name="keywords" content="' . e($keywords) . "\">\n";
        }

        // Canonical
        $canonical = $this->get('canonical', request()->url());
        $html .= '    <link rel="canonical" href="' . e($canonical) . "\">\n";

        // Robots
        $robots = $this->get('robots', config('seo.defaults.robots'));
        $html .= '    <meta name="robots" content="' . e($robots) . "\">\n";

        // Open Graph
        $ogTitle = $this->get('og_title', $title);
        $ogDesc = $this->get('og_description', $desc);
        $ogImage = $this->get('og_image', config('seo.defaults.og_image'));
        $ogType = $this->get('og_type', config('seo.defaults.og_type'));

        $html .= '    <meta property="og:title" content="' . e($ogTitle) . "\">\n";
        $html .= '    <meta property="og:description" content="' . e($ogDesc) . "\">\n";
        $html .= '    <meta property="og:url" content="' . e($canonical) . "\">\n";
        $html .= '    <meta property="og:type" content="' . e($ogType) . "\">\n";
        $html .= '    <meta property="og:site_name" content="' . e(config('seo.defaults.title_suffix')) . "\">\n";
        if ($ogImage) {
            $html .= '    <meta property="og:image" content="' . e(url($ogImage)) . "\">\n";
            $html .= '    <meta property="og:image:width" content="1200">' . "\n";
            $html .= '    <meta property="og:image:height" content="630">' . "\n";
        }

        // Twitter Card
        $twCard = config('seo.defaults.twitter_card');
        $twTitle = $this->get('twitter_title', $ogTitle);
        $twDesc = $this->get('twitter_description', $ogDesc);
        $twImage = $this->get('twitter_image', $ogImage);

        $html .= '    <meta name="twitter:card" content="' . e($twCard) . "\">\n";
        $html .= '    <meta name="twitter:title" content="' . e($twTitle) . "\">\n";
        $html .= '    <meta name="twitter:description" content="' . e($twDesc) . "\">\n";
        if ($twImage) {
            $html .= '    <meta name="twitter:image" content="' . e(url($twImage)) . "\">\n";
        }

        // hreflang (jika multi-language)
        if (config('app.locale')) {
            $html .= '    <link rel="alternate" hreflang="' . e(config('app.locale')) . '" href="' . e($canonical) . "\">\n";
        }

        // JSON-LD Structured Data
        $allJsonLd = $this->jsonLd;
        
        // Auto-add Organization + Website schema
        $allJsonLd[] = $this->organizationSchema();
        $allJsonLd[] = $this->websiteSchema();

        // Auto-add Course schema jika model adalah Pelatihan
        if ($this->model && get_class($this->model) === \App\Models\Pelatihan::class) {
            $courseSchema = $this->courseSchema();
            if ($courseSchema) {
                $allJsonLd[] = $courseSchema;
            }
        }

        foreach ($allJsonLd as $schema) {
            $html .= '    <script type="application/ld+json">' . "\n";
            $html .= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            $html .= '    </script>' . "\n";
        }

        return $html;
    }

    /**
     * Dapatkan nilai tertentu dari data SEO.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Dapatkan title dengan format yang benar (auto-suffix).
     */
    private function getTitle(): string
    {
        $title = $this->get('title', config('seo.defaults.title'));
        $separator = config('seo.defaults.title_separator');
        $suffix = config('seo.defaults.title_suffix');
        $maxLength = config('seo.title.max_length', 60);

        $full = $title . $separator . $suffix;

        // Truncate jika melebihi max length
        if (mb_strlen($full) > $maxLength) {
            $titleMax = $maxLength - mb_strlen($separator) - mb_strlen($suffix) - 3;
            if ($titleMax > 0) {
                $title = mb_substr($title, 0, $titleMax) . '...';
            }
            $full = $title . $separator . $suffix;
        }

        return $full;
    }

    /**
     * Reset semua data (untuk request berikutnya).
     */
    public function reset(): void
    {
        $this->data = [];
        $this->jsonLd = [];
        $this->model = null;
        $this->seoMeta = null;
    }
}
