@extends('layouts/layoutMaster')

@section('title', 'Edit Anggota KTA')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Admin / Manajemen Anggota KTA /</span> Edit Data
</h4>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <h5 class="card-header">Formulir Edit Anggota KTA</h5>
      <div class="card-body">
        <form action="{{ route('admin.kta-members.update', $ktaMember->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik', $ktaMember->nik) }}" maxlength="16" required>
            @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $ktaMember->nama_lengkap) }}" required>
            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label for="status_kta" class="form-label">Status KTA <span class="text-danger">*</span></label>
            <select id="status_kta" name="status_kta" class="form-select @error('status_kta') is-invalid @enderror">
              <option value="Aktif" {{ old('status_kta', $ktaMember->status_kta) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
              <option value="Tidak Aktif" {{ old('status_kta', $ktaMember->status_kta) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            @error('status_kta') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label for="wilayah" class="form-label">Wilayah</label>
            <input type="text" class="form-control @error('wilayah') is-invalid @enderror" id="wilayah" name="wilayah" value="{{ old('wilayah', $ktaMember->wilayah) }}">
            @error('wilayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $ktaMember->keterangan) }}</textarea>
            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <button type="submit" class="btn btn-primary me-2">Update</button>
          <a href="{{ route('admin.kta-members.index') }}" class="btn btn-secondary">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
