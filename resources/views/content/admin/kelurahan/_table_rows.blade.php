@forelse($kelurahans as $index => $kel)
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium">{{ $kelurahans->firstItem() + $index }}</td>
    <td class="py-3 text-white fw-semibold" style="text-transform: uppercase;">{{ $kel->name }}</td>
    <td class="py-3">
      <span class="text-body-premium" style="font-size: 0.85rem;">
        <i class="icon-base ti tabler-map-pin text-info me-1" style="font-size: 0.85rem;"></i>
        {{ $kel->kecamatan->name ?? '-' }}
      </span>
    </td>
    <td class="py-3">
      @if($kel->users->count() > 0)
        @foreach($kel->users as $koordinator)
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="icon-base ti tabler-user text-primary" style="font-size: 0.85rem;"></i>
            <span class="text-white" style="font-size: 0.85rem;">{{ $koordinator->name }}</span>
          </div>
        @endforeach
      @else
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      @endif
    </td>
    <td class="py-3">
      @if($kel->users->count() > 0)
        @foreach($kel->users as $koordinator)
          <div class="mb-1" style="font-size: 0.85rem;">
            @if($koordinator->whatsapp)
              <span class="text-body-premium">
                <i class="icon-base ti tabler-brand-whatsapp text-success me-1" style="font-size: 0.85rem;"></i>
                {{ $koordinator->whatsapp }}
              </span>
            @elseif($koordinator->phone)
              <span class="text-body-premium">
                <i class="icon-base ti tabler-phone text-info me-1" style="font-size: 0.85rem;"></i>
                {{ $koordinator->phone }}
              </span>
            @else
              <span class="text-body-premium">—</span>
            @endif
          </div>
        @endforeach
      @else
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      @endif
    </td>
    <td class="text-end px-0 py-3">
      <div class="d-inline-flex gap-2">
        <a href="{{ route('admin.kelurahan.edit', $kel) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
          <i class="icon-base ti tabler-edit fs-5 text-dark"></i>
        </a>
        <form action="{{ route('admin.kelurahan.destroy', $kel) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Yakin ingin menghapus kelurahan {{ $kel->name }}?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
            <i class="icon-base ti tabler-trash fs-5"></i>
          </button>
        </form>
      </div>
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-building-off fs-1 mb-2 d-block text-warning"></i>
      Belum ada data kelurahan.
    </td>
  </tr>
@endforelse
