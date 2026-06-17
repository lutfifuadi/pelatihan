<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Performance Optimization: Menambahkan index untuk query yang sering dijalankan.
     *
     * Hasil audit performa menunjukkan beberapa tabel kekurangan index
     * yang menyebabkan full table scan pada query-query penting.
     */
    public function up(): void
    {
        // --- users ---
        // Dashboard: SELECT COUNT(*) WHERE role = 'peserta' / 'instruktur' / 'koordinator'
        // Dashboard: WHERE role = 'koordinator' AND is_active = false
        // Dashboard: WHERE role = 'koordinator' AND is_active = true
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index(['role', 'is_active'], 'idx_users_role_is_active');
        });

        // --- pelatihan ---
        // Dashboard: SELECT COUNT(*) WHERE is_active = true
        // Dashboard: ORDER BY created_at DESC LIMIT 4
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->index('is_active', 'idx_pelatihan_is_active');
            $table->index('created_at', 'idx_pelatihan_created_at');
        });

        // --- enrollments ---
        // promoteFromWaitlist: WHERE pelatihan_id = ? AND status IN ('approved', 'waitlist')
        // EnrollmentController index: WHERE status = ?
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['pelatihan_id', 'status'], 'idx_enrollments_pelatihan_status');
        });

        // --- attendances ---
        // AttendanceController index/rapport: WHERE pelatihan_id via JOIN enrollments
        // store: WHERE enrollment_id = ? AND pertemuan_ke = ?
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['enrollment_id', 'pertemuan_ke', 'status'], 'idx_attendances_enrollment_pertemuan_status');
        });

        // --- notifications ---
        // Dashboard: WHERE channel = 'whatsapp' AND status = 'sent' AND DATE(sent_at) = TODAY
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['channel', 'status', 'sent_at'], 'idx_notifications_channel_status_sent');
        });

        // --- activity_logs ---
        // Query umum: WHERE subject_type = ? AND subject_id = ?
        // Query umum: WHERE created_at BETWEEN ? AND ?
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index(['created_at', 'action'], 'idx_activity_logs_created_action');
            $table->index(['user_id', 'created_at'], 'idx_activity_logs_user_created');
        });

        // --- schedules ---
        // Query umum: WHERE pelatihan_id = ? AND is_active = true
        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['pelatihan_id', 'is_active'], 'idx_schedules_pelatihan_active');
        });

        // --- certificates ---
        // CertificateController: WHERE certificate_number = ?
        // Sudah ada unique index di certificate_number, tambahkan untuk enrollment_id
        Schema::table('certificates', function (Blueprint $table) {
            $table->index('enrollment_id', 'idx_certificates_enrollment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_role_is_active');
        });

        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropIndex('idx_pelatihan_is_active');
            $table->dropIndex('idx_pelatihan_created_at');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_pelatihan_status');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('idx_attendances_enrollment_pertemuan_status');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_channel_status_sent');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_activity_logs_created_action');
            $table->dropIndex('idx_activity_logs_user_created');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('idx_schedules_pelatihan_active');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex('idx_certificates_enrollment_id');
        });
    }
};
