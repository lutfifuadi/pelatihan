<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan unique index pada kolom whatsapp di tabel users.
     *
     * PERHATIAN: Migration ini hanya bisa dijalankan setelah data duplikat
     * pada kolom whatsapp dibersihkan terlebih dahulu.
     *
     * Berdasarkan audit per 2026-06-30, ditemukan 13 nomor WhatsApp duplikat
     * dari 449 users. Pastikan data sudah bersih sebelum migrate.
     *
     * Cek duplikat dengan query:
     *   SELECT whatsapp, COUNT(*) FROM users
     *   WHERE whatsapp IS NOT NULL AND whatsapp != ''
     *   GROUP BY whatsapp HAVING COUNT(*) > 1;
     */
    public function up(): void
    {
        // Guard: cek duplikat sebelum menambahkan unique index
        $duplicates = DB::select("
            SELECT whatsapp, COUNT(*) as total
            FROM users
            WHERE whatsapp IS NOT NULL AND whatsapp != ''
            GROUP BY whatsapp
            HAVING COUNT(*) > 1
        ");

        if (!empty($duplicates)) {
            $count = count($duplicates);
            throw new \RuntimeException(
                "❌ Migration dibatalkan: Ditemukan {$count} nomor WhatsApp duplikat di tabel users. " .
                "Bersihkan data duplikat terlebih dahulu sebelum menjalankan migration ini. " .
                "Jalankan query: SELECT whatsapp, COUNT(*) FROM users WHERE whatsapp IS NOT NULL GROUP BY whatsapp HAVING COUNT(*) > 1"
            );
        }

        Schema::table('users', function (Blueprint $table) {
            // Unique index dengan nama eksplisit agar mudah di-drop saat rollback
            $table->unique('whatsapp', 'users_whatsapp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_whatsapp_unique');
        });
    }
};
