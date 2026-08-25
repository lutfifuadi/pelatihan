<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\WhatsappNumber;
use App\Services\SEOManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SEOManager::class, function ($app) {
            return new SEOManager();
        });

        $this->app->singleton(\App\Services\SettingsManager::class, function ($app) {
            return new \App\Services\SettingsManager();
        });

        $this->app->singleton(\App\Services\FeatureManager::class, function ($app) {
            return new \App\Services\FeatureManager($app->make(\App\Services\SettingsManager::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Sinkronisasi Zona Waktu dari Setting Aplikasi / .env ──
        try {
            if (!app()->runningInConsole() || app()->runningUnitTests() === false) {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $tz = \App\Models\Setting::where('key', 'timezone')->value('value')
                        ?: config('app.timezone', 'Asia/Jakarta');
                    if ($tz) {
                        date_default_timezone_set($tz);
                        config(['app.timezone' => $tz]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silence exceptions during early migrations or install
        }

        Blade::directive('seoHead', function () {
            return "<?php echo app(\App\Services\SEOManager::class)->render(); ?>";
        });

        // ── Blade Directives untuk Feature Toggle ──
        Blade::directive('fitur', function ($expression) {
            return "<?php if (feature({$expression})): ?>";
        });
        Blade::directive('endfitur', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('notfitur', function ($expression) {
            return "<?php if (feature_is_off({$expression})): ?>";
        });
        Blade::directive('endnotfitur', function () {
            return '<?php endif; ?>';
        });

        Paginator::useBootstrapFive();

        Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
            if ($src !== null) {
                return [
                    'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
                ];
            }
            return [];
        });

        \Illuminate\Support\Facades\Validator::extend('readonly', function ($attribute, $value, $parameters, $validator) {
            return true;
        });

        View::composer(['layouts/publicLayout'], function ($view) {
            $view->with('whatsappNumbers', WhatsappNumber::active()->sorted()->get());
        });
    }
}
