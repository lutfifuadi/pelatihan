<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();

            // Foreign key ke users. Nullable karena subscription bisa anonymous.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Subscription data dari PushManager.subscribe()
            $table->text('endpoint');
            $table->text('p256dh_key');
            $table->text('auth_key');

            // Tracking device/platform
            $table->string('user_agent', 500)->nullable();
            $table->enum('platform', ['android', 'ios', 'desktop', 'unknown'])->default('unknown');

            // Timing
            $table->timestamp('subscribed_at');
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('platform');
            $table->index('expired_at');
            $table->index('subscribed_at');

            // MySQL tidak mengizinkan unique index langsung pada TEXT tanpa prefix length.
            // Gunakan unique index dengan prefix 255 karakter untuk menjaga endpoint unik.
            $connection = Schema::getConnection()->getDriverName();
            if ($connection === 'mysql') {
                $table->unique([DB::raw('endpoint(255)')], 'push_subscriptions_endpoint_unique');
            } else {
                // SQLite & lainnya — full text bisa langsung unique
                $table->unique('endpoint', 'push_subscriptions_endpoint_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
    }
};
