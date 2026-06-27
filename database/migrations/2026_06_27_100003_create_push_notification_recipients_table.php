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
        Schema::create('push_notification_recipients', function (Blueprint $table) {
            $table->id();

            // Relasi ke notifikasi yang dikirim
            $table->foreignId('notification_id')
                ->constrained('push_notifications')
                ->onDelete('cascade');

            // Relasi ke subscription penerima
            $table->foreignId('subscription_id')
                ->constrained('push_subscriptions')
                ->onDelete('cascade');

            // Status pengiriman
            $table->enum('status', ['pending', 'sent', 'failed', 'expired'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('notification_id');
            $table->index('subscription_id');
            $table->index('status');
            $table->index(['notification_id', 'status']);
            $table->index('sent_at');

            // Hindari duplikasi log pengiriman untuk pasangan notifikasi + subscription yang sama
            $table->unique(['notification_id', 'subscription_id'], 'push_notif_recipients_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notification_recipients');
    }
};
