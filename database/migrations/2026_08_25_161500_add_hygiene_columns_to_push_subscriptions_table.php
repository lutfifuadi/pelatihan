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
        Schema::table('push_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('push_subscriptions', 'endpoint_hash')) {
                $table->string('endpoint_hash', 64)->nullable()->after('endpoint');
            }
            if (!Schema::hasColumn('push_subscriptions', 'content_encoding')) {
                $table->string('content_encoding', 30)->default('aes128gcm')->after('auth_key');
            }
            if (!Schema::hasColumn('push_subscriptions', 'device_label')) {
                $table->string('device_label', 150)->nullable()->after('platform');
            }
            if (!Schema::hasColumn('push_subscriptions', 'browser')) {
                $table->string('browser', 50)->nullable()->after('device_label');
            }
            if (!Schema::hasColumn('push_subscriptions', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('browser')->index();
            }
            if (!Schema::hasColumn('push_subscriptions', 'failed_count')) {
                $table->unsignedInteger('failed_count')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('push_subscriptions', 'last_failed_at')) {
                $table->timestamp('last_failed_at')->nullable()->after('failed_count');
            }
            if (!Schema::hasColumn('push_subscriptions', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('last_failed_at');
            }
        });

        // Backfill endpoint_hash for existing records
        $subscriptions = DB::table('push_subscriptions')->whereNull('endpoint_hash')->get(['id', 'endpoint']);
        foreach ($subscriptions as $sub) {
            DB::table('push_subscriptions')->where('id', $sub->id)->update([
                'endpoint_hash' => hash('sha256', $sub->endpoint)
            ]);
        }

        // Add index on endpoint_hash if not exists
        Schema::table('push_subscriptions', function (Blueprint $table) {
            $table->index('endpoint_hash');
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('push_subscriptions', function (Blueprint $table) {
            $table->dropIndex(['endpoint_hash']);
            $table->dropIndex(['user_id', 'is_active']);
            $table->dropColumn([
                'endpoint_hash',
                'content_encoding',
                'device_label',
                'browser',
                'is_active',
                'failed_count',
                'last_failed_at',
                'last_used_at',
            ]);
        });
    }
};
