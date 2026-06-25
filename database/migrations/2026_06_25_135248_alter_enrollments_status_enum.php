<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending','approved','rejected','waitlist') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending','approved','waiting_wa_confirmation','waiting_newbimma_check','confirmed','rejected','waitlist') NOT NULL DEFAULT 'pending'");
    }
};
