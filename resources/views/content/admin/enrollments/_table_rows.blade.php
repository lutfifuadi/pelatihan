@forelse($enrollments as $index => $enrollment)
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium">{{ $enrollments->firstItem() + $index }}</td>
    <td class="py-3">
      <div class="fw-semibold text-white">{{ $enrollment->user?->name ?? 'User tidak ditemukan' }}</div>
      <div class="text-body-premium" style="font-size: 0.75rem;">{{ $enrollment->user?->email ?? '-' }}</div>
    </td>
    <td class="py-3">
      <div class="fw-semibold text-white" style="font-size: 0.85rem;">{{ $enrollment->pelatihan->nama }}</div>
      <div class="text-body-premium" style="font-size: 0.7rem;">Batch: {{ $enrollment->pelatihan->batch }}</div>
    </td>
    <td class="py-3 text-body-premium" style="font-size: 0.85rem;">
      {{ $enrollment->created_at->format('d/m/Y H:i') }}
    </td>
    <td class="py-3">
      @switch($enrollment->status)
        @case('pending')
          <span class="badge-premium badge-premium-warning">Pending</span>
          @break
        @case('approved')
          @if($enrollment->verification_code && !$enrollment->wa_confirmed_at)
            <span class="badge" style="background: rgba(234,179,8,0.15); color: #eab308; border: 1px solid rgba(234,179,8,0.3);">
              <i class="icon-base ti tabler-brand-whatsapp me-1"></i>Menunggu Chat WA
            </span>
          @elseif($enrollment->wa_confirmed_at && !$enrollment->newbimma_checked_at)
            <span class="badge" style="background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);">
              <i class="icon-base ti tabler-search me-1"></i>Cek Newbimma
            </span>
          @elseif($enrollment->newbimma_result === 'valid')
            <span class="badge" style="background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3);">
              <i class="icon-base ti tabler-circle-check me-1"></i>Terkonfirmasi
            </span>
          @elseif($enrollment->newbimma_result === 'invalid')
            <span class="badge" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3);">
              <i class="icon-base ti tabler-x me-1"></i>Ditolak Newbimma
            </span>
          @else
            <span class="badge-premium badge-premium-success">Approved</span>
            @if($enrollment->waitlist_promoted_at)
              <div style="font-size: 0.65rem; color: #93c5fd; margin-top: 2px;">Dari cadangan</div>
            @endif
          @endif
          @break
        @case('rejected')
          <span class="badge-premium badge-premium-danger">Ditolak</span>
          @break
        @case('waitlist')
          <span class="badge-premium badge-premium-info">Cadangan</span>
          @break
      @endswitch
    </td>
    <td class="text-end px-0 py-3" style="white-space: nowrap;">
      {{-- Dropdown Ubah Status --}}
      <div class="dropdown d-inline">
        <button class="btn btn-sm dropdown-toggle d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" style="border-radius: 5px; background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2); color: #93c5fd; padding: 4px 10px; font-size: 0.75rem;" title="Ubah Status">
          <i class="icon-base ti tabler-arrows-exchange me-1"></i> Status
        </button>
        <ul class="dropdown-menu dropdown-menu-dark" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; min-width: 180px;">
          <li><h6 class="dropdown-header" style="color: rgba(255,255,255,0.5); font-size: 0.7rem; text-transform: uppercase;">Ubah Status Ke:</h6></li>
          <li>
            <form action="{{ route('admin.enrollments.change-status', $enrollment) }}" method="POST" class="change-status-form">
              @csrf
              <input type="hidden" name="status" value="pending">
              <input type="hidden" name="notes" value="">
              <button type="submit" class="dropdown-item" style="color: #fbbf24; font-size: 0.8rem; padding: 6px 16px;">
                ⏳ Pending
              </button>
            </form>
          </li>
          <li>
            <form action="{{ route('admin.enrollments.change-status', $enrollment) }}" method="POST" class="change-status-form">
              @csrf
              <input type="hidden" name="status" value="approved">
              <input type="hidden" name="notes" value="">
              <button type="submit" class="dropdown-item" style="color: #34d399; font-size: 0.8rem; padding: 6px 16px;">
                ✅ Approved
              </button>
            </form>
          </li>
          <li>
            <form action="{{ route('admin.enrollments.change-status', $enrollment) }}" method="POST" class="change-status-form">
              @csrf
              <input type="hidden" name="status" value="rejected">
              <input type="hidden" name="notes" value="">
              <button type="submit" class="dropdown-item" style="color: #f87171; font-size: 0.8rem; padding: 6px 16px;">
                ❌ Rejected
              </button>
            </form>
          </li>
          <li>
            <form action="{{ route('admin.enrollments.change-status', $enrollment) }}" method="POST" class="change-status-form">
              @csrf
              <input type="hidden" name="status" value="waitlist">
              <input type="hidden" name="notes" value="">
              <button type="submit" class="dropdown-item" style="color: #93c5fd; font-size: 0.8rem; padding: 6px 16px;">
                🟡 Waitlist
              </button>
            </form>
          </li>
        </ul>
      </div>

      {{-- Tombol Detail --}}
      <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn btn-outline-info btn-sm d-inline-flex align-items-center justify-content-center ms-1" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; border-color: rgba(96,165,250,0.3); color: #93c5fd;" title="Detail">
        <i class="icon-base ti tabler-eye fs-5"></i>
      </a>

      {{-- Tombol Reset --}}
      <form action="{{ route('admin.enrollments.reset', $enrollment) }}" method="POST" class="d-inline reset-enrollment-form" data-name="{{ $enrollment->user?->name ?? 'Unknown' }}" data-pelatihan="{{ $enrollment->pelatihan->nama }}">
        @csrf
        <button type="submit" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center ms-1" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; border: none; background: linear-gradient(135deg, #f59e0b, #d97706);" title="Reset pendaftaran">
          <i class="icon-base ti tabler-refresh fs-5"></i>
        </button>
      </form>

      {{-- Tombol Generate Kode Verifikasi --}}
      @if($enrollment->status === 'approved' && !$enrollment->verification_code)
        <form action="{{ route('admin.enrollments.generate-verification-code', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm"
                  style="background: #6366f1; color: white; border: none; border-radius: 5px; padding: 4px 10px;">
            <i class="icon-base ti tabler-key"></i> Generate Kode
          </button>
        </form>
      @endif

      {{-- Tombol WA Confirmation --}}
      @if($enrollment->status === 'approved' && $enrollment->verification_code && !$enrollment->wa_confirmed_at)
        <form action="{{ route('admin.enrollments.confirm-wa-chat', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm"
                  style="background: #25D366; color: white; border: none; border-radius: 5px; padding: 4px 10px;"
                  onclick="return confirm('Konfirmasi bahwa peserta sudah chat WA?')">
            <i class="icon-base ti tabler-brand-whatsapp"></i> Sudah Chat WA
          </button>
        </form>
      @endif

      {{-- Tombol Newbimma Check --}}
      @if($enrollment->status === 'approved' && $enrollment->wa_confirmed_at && !$enrollment->newbimma_checked_at)
        <a href="#" class="btn btn-sm btn-outline-primary"
           onclick="window.open('https://newbimma.example.com', '_blank')"
           style="font-size: 11px;">
          🔍 Cek Newbimma
        </a>
        <form action="{{ route('admin.enrollments.confirm-newbimma-valid', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm"
                  style="background: #22c55e; color: white; border: none; border-radius: 5px; padding: 4px 10px;"
                  onclick="return confirm('Validasi Newbimma: Pastikan peserta TIDAK TERDAFTAR di pelatihan yang sama. Lanjutkan?')">
            ✅ Valid
          </button>
        </form>
        <form action="{{ route('admin.enrollments.reject-newbimma-invalid', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sm"
                  style="background: #ef4444; color: white; border: none; border-radius: 5px; padding: 4px 10px;"
                  onclick="return confirm('Yakin ingin menolak? Peserta sudah pernah ikut pelatihan yang sama di Newbimma.')">
            ❌ Tolak
          </button>
        </form>
      @endif
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-inbox fs-1 mb-2 d-block text-warning"></i>
      Belum ada pendaftaran.
    </td>
  </tr>
@endforelse
