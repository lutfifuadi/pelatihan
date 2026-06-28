<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update Tabel `pelatihan`
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('auto_approve');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('radius_toleransi')->default(50)->after('longitude');
        });

        // 2. Update Tabel `attendances`
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('verified_method', ['QR', 'Manual'])->default('QR')->nullable()->after('status');
            $table->decimal('latitude_panitia', 10, 8)->nullable()->after('verified_method');
            $table->decimal('longitude_panitia', 11, 8)->nullable()->after('latitude_panitia');
            $table->integer('distance_from_center')->nullable()->after('longitude_panitia');
            $table->string('ip_address', 45)->nullable()->after('distance_from_center');
            $table->string('device_user', 255)->nullable()->after('ip_address');
            $table->foreignId('scanner_by')->nullable()->constrained('users')->nullOnDelete()->after('device_user');
            $table->foreignId('bypassed_by')->nullable()->constrained('users')->nullOnDelete()->after('scanner_by');
            $table->string('bypass_reason', 255)->nullable()->after('bypassed_by');
            $table->foreignId('corrected_by')->nullable()->constrained('users')->nullOnDelete()->after('bypass_reason');
            $table->timestamp('corrected_at')->nullable()->after('corrected_by');
        });

        // 3. Buat Tabel `audit_logs`
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->constrained('users')->onDelete('cascade');
            $table->enum('actor_role', ['admin', 'panitia', 'instruktur']);
            $table->enum('action_type', ['create', 'update', 'delete', 'bypass', 'correct', 'export', 'login']);
            $table->string('target_entity', 50);
            $table->bigInteger('target_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop Tabel `audit_logs`
        Schema::dropIfExists('audit_logs');

        // 2. Drop columns from Tabel `attendances`
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['scanner_by']);
            $table->dropForeign(['bypassed_by']);
            $table->dropForeign(['corrected_by']);

            $table->dropColumn([
                'verified_method',
                'latitude_panitia',
                'longitude_panitia',
                'distance_from_center',
                'ip_address',
                'device_user',
                'scanner_by',
                'bypassed_by',
                'bypass_reason',
                'corrected_by',
                'corrected_at'
            ]);
        });

        // 3. Drop columns from Tabel `pelatihan`
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'radius_toleransi'
            ]);
        });
    }
};
