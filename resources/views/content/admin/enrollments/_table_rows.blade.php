@forelse($enrollments as $index => $enrollment)
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium">{{ $enrollments->firstItem() + $index }}</td>
    <td class="py-3">
      <div class="fw-semibold text-white">{{ $enrollment->user->name }}</div>
      <div class="text-body-premium" style="font-size: 0.75rem;">{{ $enrollment->user->email }}</div>
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
          <span class="badge-premium badge-premium-success">Approved (Tahap 1)</span>
          @if($enrollment->waitlist_promoted_at)
            <div style="font-size: 0.65rem; color: #93c5fd; margin-top: 2px;">Dari cadangan</div>
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
      <form action="{{ route('admin.enrollments.reset', $enrollment) }}" method="POST" class="d-inline reset-enrollment-form" data-name="{{ $enrollment->user->name }}" data-pelatihan="{{ $enrollment->pelatihan->nama }}">
        @csrf
        <button type="submit" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center ms-1" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; border: none; background: linear-gradient(135deg, #f59e0b, #d97706);" title="Reset pendaftaran">
          <i class="icon-base ti tabler-refresh fs-5"></i>
        </button>
      </form>
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
