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
    | Static Pages SEO Configuration
    |--------------------------------------------------------------------------
    | Konfigurasi SEO untuk halaman statis yang tidak terikat model.
    | Digunakan oleh SEOManager::staticPage().
    */
    'static_pages' => [
        'home' => [
            'title' => 'Beranda',
            'description' => 'Platform pelatihan online — daftar, belajar, dan dapatkan sertifikat resmi.',
            'og_image' => '/assets/img/og-default.jpg',
        ],

        'daftar-koordinator' => [
            'title' => 'Daftar Koordinator Pelatihan | Aplikasi Pelatihan',
            'description' => 'Daftar menjadi koordinator pelatihan di Aplikasi Pelatihan. Kelola peserta dan jadwal pelatihan dengan mudah.',
            'og_image' => '/assets/img/og-register.jpg',
        ],
        'daftar-koordinator-sukses' => [
            'title' => 'Pendaftaran Koordinator Berhasil | Aplikasi Pelatihan',
            'description' => 'Pendaftaran koordinator pelatihan Anda telah berhasil dikirim. Tim kami akan menghubungi Anda.',
            'og_image' => '/assets/img/og-success.jpg',
        ],
        'daftar-sukses' => [
            'title' => 'Pendaftaran Berhasil | Aplikasi Pelatihan',
            'description' => 'Pendaftaran Anda telah berhasil. Silakan cek email untuk informasi selanjutnya.',
            'og_image' => '/assets/img/og-success.jpg',
        ],
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
            // Hanya model publik — User tidak perlu masuk sitemap (privasi)
        ],
        'static_urls' => [
            ['loc' => '/', 'priority' => 1.0, 'changefreq' => 'daily'],
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
