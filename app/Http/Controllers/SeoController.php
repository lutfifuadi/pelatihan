<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SeoController extends Controller
{
    /**
     * Generate XML Sitemap dinamis.
     */
    public function sitemap()
    {
        $sitemapConfig = config('seo.sitemap');

        if (!$sitemapConfig['enabled']) {
            abort(404);
        }

        // Caching
        $cacheKey = 'sitemap_xml';
        if ($sitemapConfig['cache_enabled'] && Cache::has($cacheKey)) {
            $xml = Cache::get($cacheKey);
            return response($xml, 200, ['Content-Type' => 'application/xml']);
        }

        $urls = [];

        // Static URLs dari config
        foreach ($sitemapConfig['static_urls'] as $static) {
            $urls[] = [
                'loc' => url($static['loc']),
                'lastmod' => now()->toW3cString(),
                'changefreq' => $static['changefreq'] ?? 'daily',
                'priority' => $static['priority'] ?? 0.8,
            ];
        }

        // Dynamic URLs dari model-model yang terdaftar
        foreach ($sitemapConfig['models'] as $modelClass => $options) {
            try {
                $model = app($modelClass);
                $items = $model->query();

                // Jika model pake trait HasSeo, ambil data dari relasi
                $routeName = $this->guessRouteForModel($modelClass);

                $records = $items->get();
                foreach ($records as $record) {
                    $url = route($routeName, $record, false);

                    // Priority & changefreq dari SEO metadata jika ada
                    $priority = $options['priority'] ?? 0.8;
                    $changefreq = $options['changefreq'] ?? 'weekly';

                    if (method_exists($record, 'seo') && $record->seo) {
                        $priority = $record->seo->sitemap_priority ?? $priority;
                        $changefreq = $record->seo->sitemap_changefreq ?? $changefreq;
                    }

                    $urls[] = [
                        'loc' => url($url),
                        'lastmod' => $record->updated_at?->toW3cString() ?? now()->toW3cString(),
                        'changefreq' => $changefreq,
                        'priority' => (float) $priority,
                    ];
                }
            } catch (\Exception $e) {
                // Skip model yang error
                continue;
            }
        }

        // Sort by priority descending
        usort($urls, fn($a, $b) => $b['priority'] <=> $a['priority']);

        $xml = $this->buildSitemapXml($urls);

        // Cache
        if ($sitemapConfig['cache_enabled']) {
            Cache::put($cacheKey, $xml, $sitemapConfig['cache_duration']);
        }

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * Build XML string dari array URLs.
     */
    private function buildSitemapXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . e($url['lastmod']) . "</lastmod>\n";
            $xml .= "    <changefreq>" . e($url['changefreq']) . "</changefreq>\n";
            $xml .= "    <priority>" . number_format($url['priority'], 1) . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }

    /**
     * Tebak route name untuk model.
     */
    private function guessRouteForModel(string $modelClass): string
    {
        $map = [
            \App\Models\Pelatihan::class => 'pelatihan.show',
            \App\Models\User::class => 'profile.show',
        ];

        return $map[$modelClass] ?? '';
    }

    /**
     * Generate robots.txt dinamis.
     */
    public function robots()
    {
        $robotsConfig = config('seo.robots');
        
        if (!$robotsConfig['enabled']) {
            // Default: allow all
            $content = "User-agent: *\nAllow: /\n";
            return response($content, 200, ['Content-Type' => 'text/plain']);
        }

        $content = "User-agent: *\n";

        // Allow
        foreach ($robotsConfig['allow'] as $path) {
            $content .= "Allow: {$path}\n";
        }

        // Disallow
        foreach ($robotsConfig['disallow'] as $path) {
            $content .= "Disallow: {$path}\n";
        }

        // Sitemap
        $content .= "\nSitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
