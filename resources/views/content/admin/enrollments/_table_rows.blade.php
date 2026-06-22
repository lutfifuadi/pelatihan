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
          <span class="badge-premium badge-premium-success">Approved</span>
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
    <td class="text-end px-0 py-3">
      @if($enrollment->status === 'pending')
        <form action="{{ route('admin.enrollments.approve', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-success btn-action">
            <i class="icon-base ti tabler-check me-1"></i> Approve
          </button>
        </form>
        <form action="{{ route('admin.enrollments.waitlist', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-info btn-action">
            <i class="icon-base ti tabler-clock me-1"></i> Cadangan
          </button>
        </form>
        <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $enrollment->id }}">
          <i class="icon-base ti tabler-x me-1"></i> Tolak
        </button>

        {{-- Modal Reject --}}
        <div class="modal fade" id="rejectModal{{ $enrollment->id }}" tabindex="-1">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
              <div class="modal-header border-0">
                <h6 class="text-white fw-bold mb-0">Tolak Pendaftaran</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <form action="{{ route('admin.enrollments.reject', $enrollment) }}" method="POST">
                @csrf
                <div class="modal-body">
                  <p class="text-body-premium small mb-2">Alasan penolakan (opsional):</p>
                  <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Kuota penuh, tidak memenuhi syarat..." style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px; font-size: 0.85rem;"></textarea>
                </div>
                <div class="modal-footer border-0">
                  <button type="button" class="btn btn-secondary btn-action" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7);">Batal</button>
                  <button type="submit" class="btn btn-danger btn-action">Ya, Tolak</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      @elseif($enrollment->status === 'waitlist')
        <form action="{{ route('admin.enrollments.promote', $enrollment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-success btn-action">
            <i class="icon-base ti tabler-arrow-up me-1"></i> Promosikan
          </button>
        </form>
        <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $enrollment->id }}">
          <i class="icon-base ti tabler-x me-1"></i> Tolak
        </button>
        {{-- Modal Reject --}}
        <div class="modal fade" id="rejectModal{{ $enrollment->id }}" tabindex="-1">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px;">
              <div class="modal-header border-0">
                <h6 class="text-white fw-bold mb-0">Tolak Pendaftaran</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <form action="{{ route('admin.enrollments.reject', $enrollment) }}" method="POST">
                @csrf
                <div class="modal-body">
                  <p class="text-body-premium small mb-2">Alasan penolakan (opsional):</p>
                  <textarea name="notes" class="form-control" rows="3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #f8fafc; border-radius: 5px; font-size: 0.85rem;"></textarea>
                </div>
                <div class="modal-footer border-0">
                  <button type="button" class="btn btn-secondary btn-action" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-danger btn-action">Ya, Tolak</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      @else
        <span class="text-body-premium" style="font-size: 0.8rem;">
          @if($enrollment->approved_at)
            {{ $enrollment->approved_at->format('d/m/Y') }}
          @elseif($enrollment->rejected_at)
            {{ $enrollment->rejected_at->format('d/m/Y') }}
          @endif
        </span>
      @endif

      <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn btn-outline-info btn-action ms-1" style="border-color: rgba(96,165,250,0.2); color: #93c5fd;" title="Detail">
        <i class="icon-base ti tabler-eye"></i>
      </a>
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
