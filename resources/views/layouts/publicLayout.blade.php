@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
  $configData = Helper::appClasses();
  $isFront = true;

  /* Display elements */
  $customizerHidden = $customizerHidden ?? '';
@endphp

@extends('layouts/commonMaster')

@section('layoutContent')
  <!-- Content -->
  @yield('content')
  <!--/ Content -->

  {{-- Floating WhatsApp Support (hanya untuk halaman publik) --}}
  @includeWhen(isset($whatsappNumbers) && $whatsappNumbers->isNotEmpty(), 'components.floating-whatsapp')
@endsection
