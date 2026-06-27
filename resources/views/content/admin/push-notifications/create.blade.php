@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Buat Push Notification')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold mb-4">Buat Push Notification</h4>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.push-notifications.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
          <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}" maxlength="100" required>
          @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="body" class="form-label">Teks Pesan <span class="text-danger">*</span></label>
          <textarea name="body" id="body" rows="4" class="form-control @error('body') is-invalid @enderror"
                    maxlength="255" required>{{ old('body') }}</textarea>
          @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="link_url" class="form-label">Link URL</label>
          <input type="url" name="link_url" id="link_url" class="form-control @error('link_url') is-invalid @enderror"
                 value="{{ old('link_url') }}" placeholder="https://pelatihanku.com/...">
          @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Target Penerima <span class="text-danger">*</span></label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="target_type" id="target_all" value="all"
                   {{ old('target_type', 'all') === 'all' ? 'checked' : '' }}>
            <label class="form-check-label" for="target_all">Semua Pendaftar</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="target_type" id="target_filtered" value="filtered"
                   {{ old('target_type') === 'filtered' ? 'checked' : '' }}>
            <label class="form-check-label" for="target_filtered">Filter</label>
          </div>
          @error('target_type')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div id="filter-container" class="mb-3 {{ old('target_type') === 'filtered' ? '' : 'd-none' }}">
          <div class="mb-2">
            <label class="form-label">Status Enrollment</label>
            <select name="target_filters[status][]" class="form-select" multiple>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="waiting_wa_confirmation">Waiting WA Confirmation</option>
              <option value="waiting_newbimma_check">Waiting Newbimma Check</option>
              <option value="confirmed">Confirmed</option>
              <option value="rejected">Rejected</option>
              <option value="waitlist">Waitlist</option>
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Daerah / Kelurahan</label>
            <select name="target_filters[daerah][]" class="form-select" multiple>
              @foreach($kelurahans as $kelurahan)
                <option value="{{ $kelurahan->id }}">{{ $kelurahan->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Pelatihan</label>
            <select name="target_filters[pelatihan][]" class="form-select" multiple>
              @foreach($pelatihans as $pelatihan)
                <option value="{{ $pelatihan->id }}">{{ $pelatihan->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan & Lanjutkan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.querySelectorAll('input[name="target_type"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
      document.getElementById('filter-container').classList.toggle('d-none', this.value !== 'filtered');
    });
  });
</script>
@endsection
