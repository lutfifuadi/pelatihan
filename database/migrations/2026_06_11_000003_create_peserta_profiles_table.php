<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('bulan_lahir')->nullable();
            $table->string('tahun_lahir')->nullable();
            $table->string('nik')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kodepos')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->json('link_medsos')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('nama_institusi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('status_pekerjaan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->json('bidang_minat')->nullable();
            $table->text('tujuan_pelatihan')->nullable();
            $table->string('preferensi_jadwal')->nullable();
            $table->string('preferensi_mode')->nullable();
            $table->string('foto_profil')->nullable();
            $table->string('scan_ktp')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_profiles');
    }
};
