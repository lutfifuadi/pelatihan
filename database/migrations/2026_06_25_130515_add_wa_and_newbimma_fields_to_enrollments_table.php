<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('enrollments', 'verification_code')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->string('verification_code', 20)->nullable()->unique()->after('notes');
                $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
                $table->timestamp('wa_confirmed_at')->nullable()->after('verification_code_expires_at');
                $table->foreignId('wa_confirmed_by')->nullable()->constrained('users')->after('wa_confirmed_at');
                $table->timestamp('newbimma_checked_at')->nullable()->after('wa_confirmed_by');
                $table->foreignId('newbimma_checked_by')->nullable()->constrained('users')->after('newbimma_checked_at');
                $table->enum('newbimma_result', ['valid', 'invalid'])->nullable()->after('newbimma_checked_by');
            });
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending','approved','waiting_wa_confirmation','waiting_newbimma_check','confirmed','rejected','waitlist') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending','approved','rejected','waitlist') NOT NULL DEFAULT 'pending'");
        }

        if (Schema::hasColumn('enrollments', 'verification_code')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropForeign(['wa_confirmed_by']);
                $table->dropForeign(['newbimma_checked_by']);
                $table->dropColumn([
                    'verification_code',
                    'verification_code_expires_at',
                    'wa_confirmed_at',
                    'wa_confirmed_by',
                    'newbimma_checked_at',
                    'newbimma_checked_by',
                    'newbimma_result',
                ]);
            });
        }
    }
};
