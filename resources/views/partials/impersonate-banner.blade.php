@if(session()->has('impersonator_id'))
  <div style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 9999; background: linear-gradient(90deg, #b45309, #d97706); color: #fff; padding: 10px 20px; font-family: 'Sora', sans-serif; box-shadow: 0 -4px 15px rgba(0,0,0,0.3); border-top: 2px solid #fbbf24;">
    <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
        <i class="icon-base ti tabler-user-shield fs-4 animate-pulse" style="animation: pulse 2s infinite;"></i>
        <span class="fw-semibold small-mobile-text">
          <strong>MODE LOGIN AS:</strong> Anda saat ini masuk sebagai <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}).
        </span>
      </div>
      <div>
        <form action="{{ route('impersonate.leave') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-sm btn-light text-warning fw-bold px-3 py-1 d-flex align-items-center gap-1 border-0 hover-scale" style="background-color: #fff; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); color: #b45309 !important;">
            <i class="icon-base ti tabler-logout fs-6"></i>
            Kembali ke Admin
          </button>
        </form>
      </div>
    </div>
  </div>

  <style>
    body {
      padding-bottom: 50px !important;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.6; }
    }
    .hover-scale {
      transition: transform 0.2s ease;
    }
    .hover-scale:hover {
      transform: scale(1.05);
    }
    @media (max-width: 576px) {
      .small-mobile-text {
        font-size: 0.8rem;
      }
    }
  </style>
@endif

