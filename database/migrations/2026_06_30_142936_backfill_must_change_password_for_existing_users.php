<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill: set must_change_password = false untuk semua user yang sudah ada
     * sebelum fitur ini diimplementasikan (created_at < 2026-06-30).
     *
     * Alasan: Kolom must_change_password ditambahkan pada 2026-06-30 dengan
     * DEFAULT true. User-user lama yang sudah punya password sendiri tidak perlu
     * dipaksa ganti password — flag ini hanya relevan untuk akun baru yang
     * dibuat oleh admin dengan password sementara.
     *
     * Catatan: Tidak perlu Schema::table() karena ini hanya update data,
     * bukan perubahan skema.
     */
    public function up(): void
    {
        // Hitung dulu berapa user yang terdampak untuk logging
        $count = DB::table('users')
            ->where('must_change_password', true)
            ->where('created_at', '<', '2026-06-30 00:00:00')
            ->count();

        if ($count === 0) {
            // Tidak ada yang perlu diupdate
            return;
        }

        // Update dalam batch untuk efisiensi (hindari lock terlalu lama pada tabel besar)
        DB::table('users')
            ->where('must_change_password', true)
            ->where('created_at', '<', '2026-06-30 00:00:00')
            ->update(['must_change_password' => false]);
    }

    /**
     * Reverse: kembalikan semua user lama ke must_change_password = true.
     * (Digunakan jika migration perlu di-rollback — jarang terjadi di production)
     */
    public function down(): void
    {
        DB::table('users')
            ->where('created_at', '<', '2026-06-30 00:00:00')
            ->update(['must_change_password' => true]);
    }
};
