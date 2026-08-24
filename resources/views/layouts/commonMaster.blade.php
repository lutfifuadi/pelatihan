<!DOCTYPE html>
@php
  use Illuminate\Support\Str;
  use App\Helpers\Helpers;

  $menuFixed =
      $configData['layout'] === 'vertical'
          ? $menuFixed ?? ''
          : ($configData['layout'] === 'front'
              ? ''
              : $configData['headerType']);
  $navbarType =
      $configData['layout'] === 'vertical'
          ? $configData['navbarType']
          : ($configData['layout'] === 'front'
              ? 'layout-navbar-fixed'
              : '');
  $isFront = ($isFront ?? '') == true ? 'Front' : '';
  $contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';

  // Get skin name from configData - only applies to admin layouts
  $isAdminLayout = !Str::contains($configData['layout'] ?? '', 'front');
  $skinName = $isAdminLayout ? $configData['skinName'] ?? 'default' : 'default';

  // Get semiDark value from configData - only applies to admin layouts
  $semiDarkEnabled = $isAdminLayout && filter_var($configData['semiDark'] ?? false, FILTER_VALIDATE_BOOLEAN);

  // Generate primary color CSS if color is set
  $primaryColorCSS = '';
  if (isset($configData['color']) && $configData['color']) {
      $primaryColorCSS = Helpers::generatePrimaryColorCSS($configData['color']);
  }

@endphp

<html lang="id"
  class="{{ $navbarType ?? '' }} {{ $contentLayout ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
  dir="ltr" data-skin="{{ $skinName }}" data-assets-path="{{ asset('/assets') . '/' }}"
  data-base-url="{{ url('/') }}" data-framework="laravel" data-template="{{ $configData['layout'] }}-menu-template"
  data-bs-theme="{{ $configData['theme'] }}" @if ($isAdminLayout && $semiDarkEnabled) data-semidark-menu="true" @endif>

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  {{-- ===== SEO META TAGS (Dinamis dari SEOManager) ===== --}}
  @seoHead

  {{-- Fallback: jika tidak ada data dari SEOManager, gunakan yield --}}
  @hasSection('title')
    <title>@yield('title') | {{ config('variables.templateName') ?? config('app.name') }}</title>
  @endif

  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  {{-- ===== PWA / Mobile Meta Tags ===== --}}
  <meta name="application-name" content="Pelatihanku">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#7367f0">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Pelatihanku">
  <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
  <link rel="mask-icon" href="{{ asset('icons/icon.svg') }}" color="#7367f0">
  <meta name="msapplication-TileColor" content="#7367f0">
  <meta name="msapplication-TileImage" content="{{ asset('icons/icon-192x192.png') }}">
  {{-- ===== End PWA Meta Tags ===== --}}

  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles' . $isFront)

  <!-- Component-pushed styles (e.g. floating WhatsApp) -->
  @stack('styles')

  @if (
      $primaryColorCSS &&
          (config('custom.custom.primaryColor') ||
              isset($_COOKIE['admin-primaryColor']) ||
              isset($_COOKIE['front-primaryColor'])))
    <!-- Primary Color Style -->
    <style id="primary-color-style">
      {!! $primaryColorCSS !!}
    </style>
  @endif

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes' . $isFront)
</head>

<body>
  @include('partials.impersonate-banner')
  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  {{-- remove while creating package --}}
  {{-- remove while creating package end --}}

  {{-- ===== PWA Helper: iOS install prompt detection ===== --}}
  @vite(['resources/js/pwa-helper.js'])
  {{-- ===== End PWA Helper ===== --}}

  {{-- ===== Push Notification: Client-side subscription ===== --}}
  @if(feature('fitur_push_notification'))
    @vite(['resources/js/push-subscription.js'])
  @endif
  {{-- ===== End Push Notification ===== --}}

  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scripts' . $isFront)

  {{-- ===== PWA: Register Service Worker ===== --}}
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('{{ asset("sw.js") }}').then(function(registration) {
          console.log('PWA: ServiceWorker registered with scope:', registration.scope);

          // Check for updates
          registration.addEventListener('updatefound', function() {
            const newWorker = registration.installing;
            console.log('PWA: New service worker installing...');
          });
        }).catch(function(err) {
          console.log('PWA: ServiceWorker registration failed: ', err);
        });
      });
    }
  </script>
  {{-- ===== End PWA SW Registration ===== --}}

  {{-- Push Notification Overlay (hidden by default, shown after delay) --}}
  @if(feature('fitur_push_notification'))
  <div id="push-notification-overlay" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4"
       style="background: rgba(0, 0, 0, 0.6);">
    <x-push-subscription-toggle />
  </div>
  @endif
  {{-- ===== End Push Notification Overlay ===== --}}

</body>

</html>
