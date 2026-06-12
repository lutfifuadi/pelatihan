@extends('installer.layout')

@section('step-chip',    'Langkah 3 — Instalasi')
@section('step-num',     '3')
@section('progress',     '100')
@section('step-title',   'Admin & Eksekusi')
@section('step-desc',    'Buat akun admin dan jalankan instalasi final.')
@section('form-action',  route('installer.process'))

@section('content')
    <div class="notice notice-ok" style="margin-bottom: 22px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>Database terhubung! Langkah terakhir — isi data admin di bawah.</span>
    </div>

    <div class="section-label" style="display: flex; align-items: center; gap: 10px; margin: 0 0 14px; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted);">
        <span>Aplikasi</span>
        <span style="flex: 1; height: 1px; background: var(--border);"></span>
    </div>

    <div class="field">
        <label class="lbl">Nama Aplikasi</label>
        <div class="inp-wrap has-icon">
            <span class="inp-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </span>
            <input type="text" name="app_name" value="{{ old('app_name', 'Aplikasi Pelatihan') }}" placeholder="Aplikasi Pelatihan" required>
        </div>
    </div>

    <div class="section-label" style="display: flex; align-items: center; gap: 10px; margin: 18px 0 14px; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted);">
        <span>Admin</span>
        <span style="flex: 1; height: 1px; background: var(--border);"></span>
    </div>

    <div class="field">
        <label class="lbl">Nama Admin</label>
        <div class="inp-wrap has-icon">
            <span class="inp-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input type="text" name="admin_name" value="{{ old('admin_name') }}" placeholder="Nama lengkap admin" required>
        </div>
    </div>

    <div class="field">
        <label class="lbl">Email Admin</label>
        <div class="inp-wrap has-icon">
            <span class="inp-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            <input type="email" name="admin_email" value="{{ old('admin_email') }}" placeholder="admin@example.com" required>
        </div>
    </div>

    <div class="field">
        <label class="lbl">Password Admin</label>
        <div class="inp-wrap has-icon">
            <span class="inp-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" name="admin_password" id="admin_password" placeholder="Minimal 6 karakter" minlength="6" required>
        </div>
    </div>
@endsection

@section('foot-l')
    <a href="{{ route('installer.step2') }}" class="btn btn-ghost">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali
    </a>
@endsection

@section('foot-r')
    <button type="submit" class="btn btn-success btn-wide" id="btn-submit" data-loading="Menginstal...">
        <span data-label>Instal Sekarang!</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
    </button>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var $form = $('#installer-form');
    var $overlay = $('#loading-overlay');

    $form.on('submit', function(e) {
        e.preventDefault();

        var $btn = $('#btn-submit');
        $btn.prop('disabled', true);
        var label = $btn.find('[data-label]');
        label.text('Menginstal...');

        $overlay.addClass('active');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Instalasi gagal.');
                    $overlay.removeClass('active');
                    $btn.prop('disabled', false);
                    label.text('Instal Sekarang!');
                }
            },
            error: function(xhr) {
                $overlay.removeClass('active');
                var msg = 'Terjadi kesalahan server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert('Error: ' + msg);
                $btn.prop('disabled', false);
                label.text('Instal Sekarang!');
            }
        });
    });
});
</script>
@endsection
