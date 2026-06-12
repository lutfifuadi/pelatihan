@extends('layouts.commonMaster')

@section('content')
<div class="container py-5">
    <h1>{{ $pelatihan->nama }}</h1>
    <p>{{ $pelatihan->deskripsi }}</p>
    
    <div class="mt-4">
        <strong>Batch:</strong> {{ $pelatihan->batch }}<br>
        <strong>Tanggal:</strong> {{ $pelatihan->tanggal_mulai?->format('d M Y') }} - {{ $pelatihan->tanggal_selesai?->format('d M Y') }}<br>
        <strong>Kuota:</strong> {{ $pelatihan->kuota }}<br>
        <strong>Status:</strong> {{ $pelatihan->is_active ? 'Aktif' : 'Tidak Aktif' }}
    </div>
</div>
@endsection
