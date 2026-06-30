<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom must_change_password ke tabel users.
     *
     * Kolom ini digunakan untuk fitur "Tambah User Baru oleh Admin":
     * - Saat admin membuat akun user, password-nya bersifat sementara.
     * - Flag ini memaksa user mengganti password pada login pertama.
     * - Default true agar semua akun baru wajib ganti password.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Diposisikan setelah 'is_active' agar logika user management berurutan
            $table->boolean('must_change_password')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('must_change_password');
        });
    }
};
