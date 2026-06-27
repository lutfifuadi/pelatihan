@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Detail Push Notification')

@section('page-style')
<style>
  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 5px !important;
  }
  .text-body-premium { color: rgba(255, 255, 255, 0.65) !important; }
  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-size: 0.75rem;
  }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold mb-4">Detail Push Notification</h4>

  <div class="glass-card-premium mb-4 p-4">
    <h5 class="text-white">{{ $notification->title }}</h5>
    <p class="text-body-premium">{{ $notification->body }}</p>

    <div class="row g-2 mt-3">
        <div class="col-md-6">
            <div class="text-body-premium small">Link URL: {{ $notification->link_url ?? '-' }}</div>
            <div class="text-body-premium small">Target: {{ $notification->target_type === 'all' ? 'Semua' : 'Filter' }}</div>
        </div>
        <div class="col-md-6">
            <div class="text-body-premium small">Total Target: {{ $notification->total_target }}</div>
            <div class="text-body-premium small">Waktu Kirim: {{ $notification->sent_at?->format('d M Y H:i') ?? 'Belum' }}</div>
        </div>
    </div>

    @if(!$notification->sent_at)
        <form action="{{ route('admin.push-notifications.send', $notification) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Kirim Sekarang</button>
        </form>
    @endif
    <a href="{{ route('admin.push-notifications.index') }}" class="btn btn-secondary btn-sm mt-3">Kembali</a>
  </div>

  <div class="glass-card-premium p-4">
    <h5 class="text-white mb-3">Log Penerima</h5>
    @if($recipients->isEmpty())
      <p class="text-body-premium">Belum ada log.</p>
    @else
      <div class="table-responsive">
        <table class="table table-hover table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
              <th class="text-body-premium small">ID Sub</th>
              <th class="text-body-premium small">Status</th>
              <th class="text-body-premium small">Error</th>
              <th class="text-body-premium small">Waktu</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recipients as $recipient)
              <tr>
                <td>{{ $recipient->subscription?->id ?? '-' }}</td>
                <td>
                  <span class="badge-premium">{{ $recipient->status }}</span>
                </td>
                <td class="small text-body-premium">{{ $recipient->error_message ?? '-' }}</td>
                <td class="small">{{ $recipient->sent_at?->format('d M Y H:i') ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $recipients->links() }}
    @endif
  </div>
</div>
@endsection
