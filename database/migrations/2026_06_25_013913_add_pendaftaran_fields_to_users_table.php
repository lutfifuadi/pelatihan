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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status_tokoh')->nullable();
            $table->string('sumber_informasi')->nullable();
            $table->string('sumber_informasi_detail')->nullable();
            $table->text('google_drive_photo_url')->nullable();
            $table->text('google_drive_ktp_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status_tokoh',
                'sumber_informasi',
                'sumber_informasi_detail',
                'google_drive_photo_url',
                'google_drive_ktp_url',
            ]);
        });
    }
};
