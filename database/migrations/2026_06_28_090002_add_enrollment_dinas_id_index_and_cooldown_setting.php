<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom dinas_id di tabel enrollments untuk redundansi denormalisasi,
     * mengisi nilai dinas_id dari tabel pelatihan untuk data existing,
     * menambahkan index komposit (user_id, pelatihan_id, dinas_id),
     * dan menambahkan pengaturan cooldown period.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom dinas_id sebagai foreign key nullable
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('dinas_id')->nullable()->after('pelatihan_id')->constrained('dinas')->onDelete('set null');
        });

        // 2. Backfill dinas_id untuk data existing berdasarkan relasi ke pelatihan
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement(<<<'SQL'
                UPDATE enrollments
                SET dinas_id = (SELECT dinas_id FROM pelatihan WHERE pelatihan.id = enrollments.pelatihan_id)
                WHERE dinas_id IS NULL
            SQL);
        } else {
            DB::statement(<<<'SQL'
                UPDATE enrollments e
                JOIN pelatihan p ON e.pelatihan_id = p.id
                SET e.dinas_id = p.dinas_id
                WHERE e.dinas_id IS NULL
            SQL);
        }

        // 3. Tambahkan index komposit untuk deteksi riwayat pendaftaran
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'pelatihan_id', 'dinas_id'], 'idx_enrollments_user_pelatihan_dinas');
        });

        // 4. Tambahkan record setting cooldown_period_days jika belum ada
        DB::table('settings')->insertOrIgnore([
            'key' => 'cooldown_period_days',
            'value' => '30',
            'group' => 'general',
            'label' => 'Jeda Pendaftaran Ulang (Hari)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus setting
        DB::table('settings')->where('key', 'cooldown_period_days')->delete();

        // Hapus index komposit
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_user_pelatihan_dinas');
        });

        // Hapus foreign key dan kolom dinas_id
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['dinas_id']);
            $table->dropColumn('dinas_id');
        });
    }
};
