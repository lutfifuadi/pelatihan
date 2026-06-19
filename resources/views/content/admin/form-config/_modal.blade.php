{{-- Modal Edit Field Config --}}
<div class="modal fade" id="modalFieldConfig" tabindex="-1" aria-labelledby="modalFieldConfigLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title text-white fw-bold" id="modalFieldConfigLabel" style="font-family: 'Sora', sans-serif;">
          Edit Field Config
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="fieldConfigForm" action="" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-body px-4 py-4">
          {{-- Read-only info row --}}
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label-custom">Section</label>
              <div class="text-white fw-semibold" id="edit_section_display" style="padding: 0.6rem 0; font-size: 0.95rem;"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label-custom">Field Key</label>
              <div class="text-white fw-semibold" id="edit_field_key_display" style="padding: 0.6rem 0; font-size: 0.95rem; font-family: monospace;"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label-custom">Tipe Field</label>
              <div class="text-white fw-semibold" id="edit_type_display" style="padding: 0.6rem 0; font-size: 0.95rem;"></div>
            </div>
          </div>

          <hr style="border-color: rgba(255,255,255,0.08); margin: 1rem 0;">

          {{-- Label --}}
          <div class="mb-3">
            <label for="edit_label" class="form-label-custom">Label</label>
            <input type="text" name="label" id="edit_label" class="form-control-custom w-100"
                   placeholder="Nama field yang tampil di form" required>
          </div>

          {{-- Placeholder --}}
          <div class="mb-3">
            <label for="edit_placeholder" class="form-label-custom">Placeholder</label>
            <input type="text" name="placeholder" id="edit_placeholder" class="form-control-custom w-100"
                   placeholder="Teks placeholder (opsional)">
          </div>

          {{-- Width --}}
          <div class="mb-3">
            <label for="edit_width" class="form-label-custom">Width</label>
            <select name="width" id="edit_width" class="form-control-custom w-100">
              <option value="full">Full (100%)</option>
              <option value="half">Half (50%)</option>
              <option value="third">Third (33%)</option>
            </select>
          </div>

          {{-- Validation Rules --}}
          <div class="mb-3">
            <label for="edit_validation_rules" class="form-label-custom">Validation Rules</label>
            <input type="text" name="validation_rules" id="edit_validation_rules" class="form-control-custom w-100"
                   placeholder="Contoh: uppercase|digits:5">
            <div class="text-body-premium mt-1" style="font-size: 0.75rem;">
              Aturan validasi Laravel, dipisah dengan pipe (|)
            </div>
          </div>

          {{-- Toggles --}}
          <div class="row g-4 mt-2">
            <div class="col-md-6">
              <div class="d-flex align-items-center gap-3 p-3" style="background: rgba(255,255,255,0.03); border-radius: 5px; border: 1px solid rgba(255,255,255,0.06);">
                <label class="toggle-switch mb-0">
                  <input type="checkbox" name="is_required" id="edit_is_required" value="1">
                  <span class="toggle-slider"></span>
                </label>
                <div>
                  <div class="text-white fw-semibold small">Required</div>
                  <div class="text-body-premium" style="font-size: 0.75rem;">Field wajib diisi</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center gap-3 p-3" style="background: rgba(255,255,255,0.03); border-radius: 5px; border: 1px solid rgba(255,255,255,0.06);">
                <label class="toggle-switch mb-0">
                  <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                  <span class="toggle-slider"></span>
                </label>
                <div>
                  <div class="text-white fw-semibold small">Active</div>
                  <div class="text-body-premium" style="font-size: 0.75rem;">Field ditampilkan di form</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer modal-footer-premium">
          <button type="button" class="btn px-4 py-2 text-white" style="background: rgba(255,255,255,0.08); border-radius: 5px; font-family: 'Sora', sans-serif; font-weight: 600;" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-glow-primary px-4 py-2 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-device-floppy"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
