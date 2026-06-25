@extends('layouts/layoutMaster')

@section('title', 'Detail Anggota KTA')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Admin / Manajemen Anggota KTA /</span> Detail
</h4>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      <h5 class="card-header">Informasi Anggota KTA</h5>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-sm-4"><strong>NIK</strong></div>
          <div class="col-sm-8">{{ $ktaMember->nik }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4"><strong>Nama Lengkap</strong></div>
          <div class="col-sm-8">{{ $ktaMember->nama_lengkap }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4"><strong>Status KTA</strong></div>
          <div class="col-sm-8">
            <span class="badge {{ $ktaMember->status_kta == 'Aktif' ? 'bg-label-success' : 'bg-label-danger' }}">
              {{ $ktaMember->status_kta }}
            </span>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4"><strong>Wilayah</strong></div>
          <div class="col-sm-8">{{ $ktaMember->wilayah ?? '-' }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4"><strong>Keterangan</strong></div>
          <div class="col-sm-8">{{ $ktaMember->keterangan ?? '-' }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4"><strong>Dibuat</strong></div>
          <div class="col-sm-8">{{ $ktaMember->created_at ? $ktaMember->created_at->format('d M Y H:i') : '-' }}</div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4"><strong>Diperbarui</strong></div>
          <div class="col-sm-8">{{ $ktaMember->updated_at ? $ktaMember->updated_at->format('d M Y H:i') : '-' }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body text-center">
        <a href="{{ route('admin.kta-members.edit', $ktaMember->id) }}" class="btn btn-primary w-100 mb-2">Edit Data</a>
        <form action="{{ route('admin.kta-members.destroy', $ktaMember->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger w-100">Hapus Data</button>
        </form>
        <a href="{{ route('admin.kta-members.index') }}" class="btn btn-secondary w-100 mt-2">Kembali</a>
      </div>
    </div>
  </div>
</div>
@endsection
