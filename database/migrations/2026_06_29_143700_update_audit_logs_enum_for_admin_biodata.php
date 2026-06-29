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
     * Menambahkan nilai 'super_admin' pada kolom enum actor_role
     * dan nilai 'update_biodata_by_admin' pada kolom enum action_type
     * di tabel audit_logs, untuk mendukung fitur Admin Edit Biodata Peserta.
     *
     * Menggunakan raw SQL MODIFY COLUMN (MySQL/MariaDB) dengan guard untuk
     * SQLite (digunakan di test environment) agar tidak error saat `php artisan test`.
     *
     * PERBAIKAN BUG: SQLite tidak mendukung MODIFY COLUMN / ENUM.
     * Guard ditambahkan sehingga migration ini hanya berjalan di MySQL/MariaDB.
     */
    public function up(): void
    {
        // Skip untuk SQLite (test environment) — MODIFY COLUMN tidak didukung SQLite
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // 1. Perluas enum actor_role: tambah 'super_admin'
        DB::statement("
            ALTER TABLE audit_logs
            MODIFY COLUMN actor_role ENUM('admin', 'super_admin', 'panitia', 'instruktur') NOT NULL
        ");

        // 2. Perluas enum action_type: tambah 'update_biodata_by_admin'
        DB::statement("
            ALTER TABLE audit_logs
            MODIFY COLUMN action_type ENUM(
                'create',
                'update',
                'delete',
                'bypass',
                'correct',
                'export',
                'login',
                'update_biodata_by_admin'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * Rollback: kembalikan enum ke nilai semula.
     * Pastikan tidak ada data dengan nilai 'super_admin' atau
     * 'update_biodata_by_admin' sebelum rollback dilakukan.
     */
    public function down(): void
    {
        // Skip untuk SQLite (test environment)
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // 1. Kembalikan actor_role ke nilai semula
        DB::statement("
            ALTER TABLE audit_logs
            MODIFY COLUMN actor_role ENUM('admin', 'panitia', 'instruktur') NOT NULL
        ");

        // 2. Kembalikan action_type ke nilai semula
        DB::statement("
            ALTER TABLE audit_logs
            MODIFY COLUMN action_type ENUM(
                'create',
                'update',
                'delete',
                'bypass',
                'correct',
                'export',
                'login'
            ) NOT NULL
        ");
    }
};
