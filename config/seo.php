<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Values
    |--------------------------------------------------------------------------
    | Nilai default untuk seluruh halaman jika tidak ada data spesifik.
    */
    'defaults' => [
        'title' => env('SEO_DEFAULT_TITLE', 'Aplikasi Pelatihan'),
        'title_separator' => ' | ',
        'title_suffix' => env('APP_NAME', 'Aplikasi Pelatihan'),
        'description' => env('SEO_DEFAULT_DESCRIPTION', 'Platform manajemen pelatihan online — daftar, belajar, dan dapatkan sertifikat resmi.'),
        'keywords' => 'pelatihan, kursus online, sertifikat, pelatihan online, aplikasi pelatihan',
        'robots' => 'index, follow',
        'og_type' => 'website',
        'og_image' => '/assets/img/og-default.jpg',
        'twitter_card' => 'summary_large_image',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    | Daftar model yang akan muncul di sitemap.xml
    */
    'sitemap' => [
        'enabled' => true,
        'cache_enabled' => true,
        'cache_duration' => 3600, // 1 jam
        'models' => [
            // Model => [priority, changefreq]
            App\Models\Pelatihan::class => [
                'priority' => 1.0,
                'changefreq' => 'daily',
            ],
            App\Models\User::class => [
                'priority' => 0.3,
                'changefreq' => 'weekly',
            ],
            // Tambahkan model lain nanti
        ],
        'static_urls' => [
            ['loc' => '/', 'priority' => 1.0, 'changefreq' => 'daily'],
            ['loc' => '/tentang', 'priority' => 0.7, 'changefreq' => 'monthly'],
            ['loc' => '/kontak', 'priority' => 0.6, 'changefreq' => 'monthly'],
            ['loc' => '/faq', 'priority' => 0.6, 'changefreq' => 'weekly'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Robots.txt Configuration
    |--------------------------------------------------------------------------
    */
    'robots' => [
        'enabled' => true,
        'allow' => ['/'],
        'disallow' => [
            '/admin/',
            '/dashboard/',
            '/api/',
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/home',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Schema.org Configuration
    |--------------------------------------------------------------------------
    */
    'schema' => [
        'organization' => [
            'name' => env('APP_NAME', 'Aplikasi Pelatihan'),
            'url' => env('APP_URL', 'http://localhost'),
            'logo' => '/assets/img/logo.png',
        ],
        'website' => [
            'name' => env('APP_NAME', 'Aplikasi Pelatihan'),
            'url' => env('APP_URL', 'http://localhost'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Title Tag Rules
    |--------------------------------------------------------------------------
    */
    'title' => [
        'max_length' => 60,
        'description_max_length' => 160,
        'truncate_suffix' => '...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media
    |--------------------------------------------------------------------------
    */
    'social' => [
        'twitter_handle' => env('SEO_TWITTER_HANDLE', ''),
        'facebook_page' => env('SEO_FACEBOOK_PAGE', ''),
        'instagram_handle' => env('SEO_INSTAGRAM_HANDLE', ''),
    ],
];
