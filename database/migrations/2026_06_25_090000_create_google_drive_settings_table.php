<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_drive_settings', function (Blueprint $table) {
            $table->id();
            $table->string('google_client_id', 255)->nullable();
            $table->text('google_client_secret')->nullable();
            $table->string('google_redirect_uri', 255)->nullable();
            $table->string('google_root_folder_id', 100)->nullable();
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->datetime('google_token_expires_at')->nullable();
            $table->boolean('is_connected')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index('is_connected');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_drive_settings');
    }
};
