<?php

if (!function_exists('seo')) {
    /**
     * Helper untuk mengakses SEOManager dari mana saja.
     * 
     * @param string|null $key
     * @param mixed $default
     * @return \App\Services\SEOManager|mixed
     */
    function seo($key = null, $default = null)
    {
        $manager = app(\App\Services\SEOManager::class);

        if ($key === null) {
            return $manager;
        }

        return $manager->get($key, $default);
    }
}
