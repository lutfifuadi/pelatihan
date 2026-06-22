@props(['for'])

@error($for)
  <span {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }} role="alert">
    <span class="fw-medium">{{ $message }}</span>
  </span>
@enderror
