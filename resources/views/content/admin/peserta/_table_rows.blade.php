@forelse($pesertas as $index => $p)
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium">{{ $pesertas->firstItem() + $index }}</td>
    <td class="py-3 text-white fw-semibold">{{ $p->name }}</td>
    <td class="py-3">
      @if($p->whatsapp)
        <span class="text-body-premium" style="font-size: 0.85rem;">
          <i class="icon-base ti tabler-brand-whatsapp text-success me-1" style="font-size: 0.85rem;"></i>
          {{ $p->whatsapp }}
        </span>
      @else
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      @endif
    </td>
    <td class="py-3">
      <span class="text-body-premium" style="font-size: 0.85rem;">
        <i class="icon-base ti tabler-map-pin text-info me-1" style="font-size: 0.85rem;"></i>
        {{ $p->kecamatan->name ?? '-' }}
      </span>
    </td>
    <td class="py-3">
      @if($p->pesertaProfile && $p->pesertaProfile->pelatihan)
        <span class="badge-premium bg-label-primary">{{ $p->pesertaProfile->pelatihan->nama }}</span>
      @else
        <span class="badge-premium bg-label-danger">Belum Memilih</span>
      @endif
    </td>
    <td class="py-3">
      <span class="badge-premium">{{ $p->created_at->format('d/m/Y') }}</span>
    </td>
    <td class="text-end px-0 py-3">
      <div class="d-inline-flex gap-2">
        <a href="{{ route('admin.peserta.show', $p) }}" class="btn btn-info btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
          <i class="icon-base ti tabler-eye fs-5"></i>
        </a>
        <form action="{{ route('admin.peserta.destroy', $p) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Yakin ingin menghapus peserta {{ $p->name }}?')">
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
    <td colspan="7" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-users-off fs-1 mb-2 d-block text-warning"></i>
      Belum ada data peserta.
    </td>
  </tr>
@endforelse
