@forelse($users as $index => $u)
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium">{{ $users->firstItem() + $index }}</td>
    <td class="py-3">
      <div class="d-flex flex-column">
        <span class="text-white fw-semibold">{{ $u->name }}</span>
        <span class="text-body-premium" style="font-size: 0.8rem;">{{ $u->email }}</span>
      </div>
    </td>
    <td class="py-3 text-body-premium" style="font-size: 0.85rem;">{{ $u->nik ?? '-' }}</td>
    <td class="py-3">
      @if($u->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $u->whatsapp) }}" target="_blank" class="text-body-premium hover-text-success" style="font-size: 0.85rem; text-decoration: none;">
          <i class="icon-base ti tabler-brand-whatsapp text-success me-1" style="font-size: 0.95rem;"></i>
          {{ $u->whatsapp }}
        </a>
      @else
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      @endif
    </td>
    <td class="py-3">
      @if($u->role === 'admin')
        <span class="badge-premium" style="background: rgba(139, 92, 246, 0.15); border-color: rgba(139, 92, 246, 0.3); color: #a78bfa;">Admin</span>
      @elseif($u->role === 'instruktur')
        <span class="badge-premium" style="background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.3); color: #22d3ee;">Instruktur</span>
      @elseif($u->role === 'koordinator')
        <span class="badge-premium" style="background: rgba(245, 158, 11, 0.15); border-color: rgba(245, 158, 11, 0.3); color: #fbbf24;">Koordinator</span>
      @else
        <span class="badge-premium" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #34d399;">Peserta</span>
      @endif
    </td>
    <td class="py-3 text-center">
      <div class="form-check form-switch d-inline-block">
        <input class="form-check-input status-switch" type="checkbox" role="switch"
          data-id="{{ $u->id }}"
          data-name="{{ $u->name }}"
          data-url="{{ route('admin.users.toggle-status', $u) }}"
          {{ $u->is_active ? 'checked' : '' }}
          {{ $u->id === auth()->id() ? 'disabled' : '' }}
          style="cursor: pointer;">
      </div>
    </td>
    <td class="py-3 text-body-premium" style="font-size: 0.85rem;">
      {{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-' }}
    </td>
    <td class="text-end px-0 py-3">
      @if($u->id !== auth()->id())
        <div class="d-inline-flex gap-1 align-items-center">
          {{-- Reset Password --}}
          <form action="{{ route('admin.users.reset-password', $u) }}" method="POST" class="d-inline reset-password-form"
            data-name="{{ $u->name }}" data-role="{{ $u->role }}">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff;" title="Reset Password">
              <i class="icon-base ti tabler-key fs-5"></i>
            </button>
          </form>

          @if($u->role !== 'admin')
            <form action="{{ route('admin.users.impersonate', $u) }}" method="POST" class="d-inline impersonate-form"
              data-name="{{ $u->name }}"
              data-avatar="{{ $u->avatar ?? '' }}"
              data-email="{{ $u->email }}"
              data-role="{{ $u->role }}"
              data-status="{{ $u->is_active ? 'aktif' : 'nonaktif' }}">
              @csrf
              <button type="submit" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; background: linear-gradient(135deg, #fbbf24, #d97706); border: none; color: #fff;" title="Login As (Impersonasi)">
                <i class="icon-base ti tabler-user-shield fs-5"></i>
              </button>
            </form>
          @endif

          <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline delete-form"
            data-name="{{ $u->name }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
              <i class="icon-base ti tabler-trash fs-5"></i>
            </button>
          </form>
        </div>
      @else
        <span class="text-body-premium small" style="font-size: 0.75rem;">(Akun Anda)</span>
      @endif
    </td>
  </tr>
@empty
  <tr>
    <td colspan="8" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-users-off fs-1 mb-2 d-block text-warning"></i>
      Tidak ada data user yang ditemukan.
    </td>
  </tr>
@endforelse
