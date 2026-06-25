@extends('layouts/layoutMaster')

@section('title', 'Manajemen Anggota KTA')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Admin /</span> Manajemen Anggota KTA
</h4>

<div class="card">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Data Anggota KTA</h5>
      <div>
        <a href="#" class="btn btn-secondary"><i class="ti ti-file-import me-1"></i> Impor dari Excel</a>
        <a href="#" class="btn btn-info"><i class="ti ti-refresh me-1"></i> Sinkron dari Google Sheet</a>
        <a href="{{ route('admin.kta-members.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Tambah Anggota</a>
      </div>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>NIK</th>
          <th>Nama Lengkap</th>
          <th>Status KTA</th>
          <th>Wilayah</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($ktaMembers as $member)
        <tr>
          <td><a href="{{ route('admin.kta-members.show', $member->id) }}" class="fw-semibold">{{ $member->nik }}</a></td>
          <td>{{ $member->nama_lengkap }}</td>
          <td><span class="badge {{ $member->status_kta == 'Aktif' ? 'bg-label-success' : 'bg-label-danger' }}">{{ $member->status_kta }}</span></td>
          <td>{{ $member->wilayah }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.kta-members.edit', $member->id) }}"><i class="ti ti-pencil me-1"></i> Edit</a>
                <form action="{{ route('admin.kta-members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="dropdown-item"><i class="ti ti-trash me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data anggota KTA.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
    <div class="card-footer">
        {{ $ktaMembers->links() }}
    </div>
</div>
@endsection
