<!-- BEGIN: Vendor JS-->

@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/node-waves/node-waves.js'])

@if ($configData['hasCustomizer'])
  @vite('resources/assets/vendor/libs/pickr/pickr.js')
@endif

@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/libs/hammer/hammer.js', 'resources/assets/vendor/js/menu.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])
<!-- END: Theme JS-->

<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->

<!-- BEGIN: SweetAlert2 Global Config -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/js/sweetalert2-global.js'])
<!-- END: SweetAlert2 Global Config -->

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

<!-- app JS -->
<script>
  window.broadcastEnabled = @json(\App\Models\Setting::where('key', 'broadcast_enabled')->value('value') ?? '1');
  window.reverbConfig = {
    key: @json(config('broadcasting.connections.reverb.key')),
    host: @json(config('broadcasting.connections.reverb.options.host')),
    port: @json(config('broadcasting.connections.reverb.options.port')),
    scheme: @json(config('broadcasting.connections.reverb.options.scheme')),
  };
</script>
@vite(['resources/js/app.js'])
<!-- END: app JS-->

@stack('modals')
@livewireScripts
