<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1. Ubah ENUM kolom status dari 4 nilai menjadi 7 nilai
     * 2. Migrasi data existing sesuai aturan transisi status
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Step 1: Perluas ENUM ke 7 nilai terlebih dahulu (safe — hanya menambah, tidak menghapus)
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending','approved','waiting_wa_confirmation','waiting_newbimma_check','confirmed','rejected','waitlist') NOT NULL DEFAULT 'pending'");

        // Step 2: Migrasi data existing — urutan prioritas (most specific first)
        DB::transaction(function () {
            // Priority 1: newbimma_result = 'valid' → confirmed
            // (peserta sudah lolos verifikasi WA dan NewBimma)
            DB::update("UPDATE enrollments SET status = 'confirmed' WHERE status = 'approved' AND newbimma_result = 'valid'");

            // Priority 2: wa_confirmed_at IS NOT NULL, menunggu pengecekan NewBimma → waiting_newbimma_check
            // (peserta sudah konfirmasi WA, belum dicek NewBimma)
            DB::update("UPDATE enrollments SET status = 'waiting_newbimma_check' WHERE status = 'approved' AND wa_confirmed_at IS NOT NULL AND newbimma_checked_at IS NULL AND newbimma_result IS NULL");

            // Priority 3: verification_code IS NOT NULL, wa_confirmed_at IS NULL → waiting_wa_confirmation
            // (peserta sudah mendapat kode verifikasi, belum konfirmasi WA)
            DB::update("UPDATE enrollments SET status = 'waiting_wa_confirmation' WHERE status = 'approved' AND verification_code IS NOT NULL AND wa_confirmed_at IS NULL");

            // Priority 4: newbimma_result = 'invalid' → tetap 'approved' (tidak diubah)
            // (keputusan Mas Lutfi — tidak ada pembatasan transisi status)
        });
    }

    /**
     * Reverse the migrations.
     *
     * 1. Kembalikan data dengan status 7-nilai ke 'approved'
     * 2. Kembalikan ENUM ke 4 nilai semula
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Step 1: Kembalikan status baru ke 'approved' sebelum mengubah ENUM
        DB::transaction(function () {
            DB::update("UPDATE enrollments SET status = 'approved' WHERE status IN ('waiting_wa_confirmation', 'waiting_newbimma_check', 'confirmed')");
        });

        // Step 2: Kembalikan ENUM ke 4 nilai
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending','approved','rejected','waitlist') NOT NULL DEFAULT 'pending'");
    }
};
