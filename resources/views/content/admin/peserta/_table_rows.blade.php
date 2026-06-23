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
      @php
        $profile = $p->pesertaProfile;
        $progress = 0;
        if ($profile) {
            $fields = ['nama_lengkap', 'nik', 'alamat_ktp', 'whatsapp', 'pendidikan_terakhir', 'nama_institusi', 'pelatihan_id', 'jawaban_pertanyaan'];
            $filled = 0;
            foreach ($fields as $f) {
                if (!empty($profile->$f)) {
                    $filled++;
                }
            }
            if ($profile->is_completed) {
                $progress = 100;
            } else {
                $progress = (int) round(($filled / count($fields)) * 100);
                if ($progress > 90) $progress = 90; // Batasi maks 90% sebelum submit final
            }
        }
      @endphp
      <div class="d-flex align-items-center gap-2">
        <div class="progress w-100" style="height: 6px; background-color: rgba(255,255,255,0.08); border-radius: 3px;">
          <div class="progress-bar {{ $progress == 100 ? 'bg-success' : ($progress > 50 ? 'bg-warning' : 'bg-danger') }}" 
               role="progressbar" 
               style="width: {{ $progress }}%; border-radius: 3px;" 
               aria-valuenow="{{ $progress }}" 
               aria-valuemin="0" 
               aria-valuemax="100">
          </div>
        </div>
        <span class="text-white-50 small fw-bold" style="font-size: 0.75rem; min-width: 35px;">{{ $progress }}%</span>
        @if($profile && $profile->is_completed)
          <span class="badge bg-success" style="font-size: 0.65rem; padding: 2px 6px;">Final</span>
        @endif
      </div>
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
