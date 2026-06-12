<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('seo_metadata')
            ->where('seoable_type', 'App\Models\User')
            ->delete();
    }

    public function down(): void
    {
        // Tidak perlu rollback — data User tidak diperlukan lagi
    }
};
