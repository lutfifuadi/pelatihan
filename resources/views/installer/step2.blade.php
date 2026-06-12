@extends('installer.layout')

@section('step-chip',    'Langkah 2 — Database')
@section('step-num',     '2')
@section('progress',     '66')
@section('step-title',   'Konfigurasi Database')
@section('step-desc',    'Hubungkan aplikasi dengan database server Anda.')
@section('form-action',  route('installer.step2Submit'))

@section('content')
    <div class="notice notice-info" style="margin-bottom: 22px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
        </svg>
        <span>Masukkan kredensial database MySQL/MariaDB Anda.</span>
    </div>

    <div id="db-test-result" style="display: none;"></div>

    <div class="grid-2">
        <div class="field">
            <label class="lbl">Host Database</label>
            <div class="inp-wrap has-icon">
                <span class="inp-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </span>
                <input type="text" name="db_host" id="db_host" value="{{ old('db_host', session('install_db_host', '127.0.0.1')) }}" placeholder="127.0.0.1" required>
            </div>
        </div>
        <div class="field">
            <label class="lbl">Port</label>
            <div class="inp-wrap has-icon">
                <span class="inp-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </span>
                <input type="text" name="db_port" id="db_port" value="{{ old('db_port', session('install_db_port', '3306')) }}" placeholder="3306" required>
            </div>
        </div>
    </div>

    <div class="field">
        <label class="lbl">Nama Database</label>
        <div class="inp-wrap has-icon">
            <span class="inp-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
            </span>
            <input type="text" name="db_name" id="db_name" value="{{ old('db_name', session('install_db_name')) }}" placeholder="nama_database" required>
        </div>
    </div>

    <div class="grid-2">
        <div class="field">
            <label class="lbl">Username</label>
            <div class="inp-wrap has-icon">
                <span class="inp-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <input type="text" name="db_user" id="db_user" value="{{ old('db_user', session('install_db_user', 'root')) }}" placeholder="root" required>
            </div>
        </div>
        <div class="field">
            <label class="lbl">Password</label>
            <div class="inp-wrap has-icon">
                <span class="inp-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <input type="password" name="db_password" id="db_password" value="{{ old('db_password', session('install_db_pass')) }}" placeholder="Password">
            </div>
        </div>
    </div>
@endsection

@section('foot-l')
    <a href="{{ route('installer.step1') }}" class="btn btn-ghost">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali
    </a>
    <button type="button" id="btn-test-connection" class="btn btn-ghost" style="color: #f59e0b; border-color: rgba(245,158,11,0.3);">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        <span data-label>Uji Koneksi</span>
    </button>
@endsection

@section('foot-r')
    <button type="submit" class="btn btn-primary btn-wide" id="btn-submit" data-loading="Menyimpan..." disabled>
        <span data-label>Simpan & Lanjut</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
        </svg>
    </button>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var $testBtn = $('#btn-test-connection');
    var $result = $('#db-test-result');
    var $submitBtn = $('#btn-submit');

    $testBtn.on('click', function() {
        var $btn = $(this);
        var $label = $btn.find('[data-label]');

        $btn.prop('disabled', true);
        $label.text('Menguji...');
        $result.hide().removeClass('notice notice-ok notice-err').empty();

        $.ajax({
            url: '{{ route("installer.step2.test") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                db_host: $('#db_host').val(),
                db_port: $('#db_port').val(),
                db_name: $('#db_name').val(),
                db_user: $('#db_user').val(),
                db_password: $('#db_password').val(),
            },
            success: function(data) {
                if (data.success) {
                    $result
                        .addClass('notice notice-ok')
                        .html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><span>' + data.message + '</span>')
                        .slideDown(200);
                    $submitBtn.prop('disabled', false);
                } else {
                    $result
                        .addClass('notice notice-err')
                        .html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><circle cx="12" cy="16" r=".5" fill="currentColor"/></svg><span>' + data.message + '</span>')
                        .slideDown(200);
                    $submitBtn.prop('disabled', true);
                }
            },
            error: function(xhr) {
                var msg = 'Gagal menghubungi server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $result
                    .addClass('notice notice-err')
                    .html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><circle cx="12" cy="16" r=".5" fill="currentColor"/></svg><span>' + msg + '</span>')
                    .slideDown(200);
                $submitBtn.prop('disabled', true);
            },
            complete: function() {
                $btn.prop('disabled', false);
                $label.text('Uji Koneksi');
            }
        });
    });
});
</script>
@endsection
