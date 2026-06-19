{{-- Modal Tambah/Edit Opsi --}}
<div class="modal fade" id="modalOption" tabindex="-1" aria-labelledby="modalOptionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title text-white fw-bold" id="modalOptionLabel" style="font-family: 'Sora', sans-serif;">
          Tambah Opsi Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="optionForm" action="{{ route('admin.form-options.store') }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="POST">

        <div class="modal-body px-4 py-4">
          {{-- Group Key --}}
          <div class="mb-3">
            <label for="group_key" class="form-label-custom">Group</label>
            <select name="group_key" id="group_key" class="form-control-custom w-100" required>
              @foreach($groups as $groupKey => $groupLabel)
                <option value="{{ $groupKey }}" {{ $activeGroup === $groupKey ? 'selected' : '' }}>
                  {{ $groupLabel }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Label --}}
          <div class="mb-3">
            <label for="label" class="form-label-custom">Label</label>
            <input type="text" name="label" id="label" class="form-control-custom w-100"
                   placeholder="Contoh: SMA" required>
          </div>

          {{-- Value --}}
          <div class="mb-3">
            <label for="value" class="form-label-custom">Value</label>
            <input type="text" name="value" id="value" class="form-control-custom w-100"
                   placeholder="Contoh: SMA" required>
          </div>

          {{-- Order & Active --}}
          <div class="row g-3">
            <div class="col-md-6">
              <label for="order" class="form-label-custom">Urutan</label>
              <input type="number" name="order" id="order" class="form-control-custom w-100"
                     min="0" value="1" required>
            </div>
            <div class="col-md-6 d-flex align-items-end pb-2">
              <div class="d-flex align-items-center gap-2">
                <label class="toggle-switch mb-0">
                  <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                  <span class="toggle-slider"></span>
                </label>
                <span class="text-body-premium small">Aktif</span>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer modal-footer-premium">
          <button type="button" class="btn px-4 py-2 text-white" style="background: rgba(255,255,255,0.08); border-radius: 5px; font-family: 'Sora', sans-serif; font-weight: 600;" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-device-floppy"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
