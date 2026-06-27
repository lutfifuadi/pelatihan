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
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();

            // Admin pengirim notifikasi
            $table->foreignId('admin_id')
                ->constrained('users')
                ->onDelete('restrict');

            // Konten notifikasi
            $table->string('title', 100);
            $table->string('body', 255);
            $table->string('link_url', 500)->nullable();

            // Targeting
            $table->enum('target_type', ['all', 'filtered'])->default('all');
            $table->json('target_filters')->nullable();
            $table->unsignedInteger('total_target')->default(0);

            // Waktu pengiriman (nullable agar bisa dibuat terlebih dahulu sebelum dikirim)
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('admin_id');
            $table->index('target_type');
            $table->index('sent_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notifications');
    }
};
