@extends('layouts.layoutMaster')

@section('title', 'Clear Cache')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="glass-card-premium px-4 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
          <h4 class="text-white fw-semibold mb-1">
            <i class="icon-base ti tabler-broom me-1"></i> Clear Cache
          </h4>
          <span class="text-body-premium" style="font-size: 0.85rem;">
            Bersihkan semua cache aplikasi untuk memastikan data selalu real-time.
          </span>
        </div>
      </div>

      <div style="background: rgba(255,255,255,0.04); border-radius: 5px; padding: 16px; margin-bottom: 20px;">
        <h6 class="text-white fw-semibold mb-2">Cache yang akan dibersihkan:</h6>
        <ul class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; line-height: 2;">
          <li>&#x1F5D1;&#xFE0F; Cache aplikasi (dashboard, settings, dll)</li>
          <li>&#x1F3A8; Compiled Blade views</li>
          <li>&#x2699;&#xFE0F; Config cache</li>
          <li>&#x1F6E3;&#xFE0F; Route cache</li>
          <li>&#x1F4E6; Compiled services & manifest</li>
        </ul>
      </div>

      <form action="{{ route('admin.cache.clear') }}" method="POST" id="clearCacheForm">
        @csrf
        <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-2" id="clearCacheBtn" style="border-radius: 5px; font-weight: 600; padding: 12px 24px;">
          <i class="icon-base ti tabler-trash fs-5"></i>
          <span id="btnText">Clear All Cache</span>
          <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width: 16px; height: 16px;"></div>
        </button>
      </form>
    </div>

    @if(session('success'))
    <div class="glass-card-premium px-4 py-4 mb-4" style="border-left: 4px solid #34d399;">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div style="background: rgba(16, 185, 129, 0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <i class="icon-base ti tabler-check" style="color: #34d399; font-size: 1.3rem;"></i>
        </div>
        <div>
          <h6 class="text-white fw-semibold mb-0">{{ session('success')['message'] ?? 'Cache berhasil dibersihkan!' }}</h6>
          <small class="text-body-premium">Selesai dalam {{ session('success')['duration'] ?? '-' }} detik</small>
        </div>
      </div>
      @if(isset(session('success')['details']))
      <ul class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; line-height: 2;">
        @foreach(session('success')['details'] as $detail)
          <li>{{ $detail }}</li>
        @endforeach
      </ul>
      @endif
    </div>
    @endif

    @if(session('error'))
    <div class="glass-card-premium px-4 py-4 mb-4" style="border-left: 4px solid #f87171;">
      <div class="d-flex align-items-center gap-3">
        <div style="background: rgba(239, 68, 68, 0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <i class="icon-base ti tabler-x" style="color: #f87171; font-size: 1.3rem;"></i>
        </div>
        <div>
          <h6 class="text-white fw-semibold mb-0">Gagal!</h6>
          <small class="text-body-premium">{{ session('error') }}</small>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection

@section('page-script')
<script>
document.getElementById('clearCacheForm')?.addEventListener('submit', function(e) {
  const btn = document.getElementById('clearCacheBtn');
  const btnText = document.getElementById('btnText');
  const spinner = document.getElementById('btnSpinner');

  btn.disabled = true;
  btnText.textContent = 'Membersihkan...';
  spinner.classList.remove('d-none');
});
</script>
@endsection
