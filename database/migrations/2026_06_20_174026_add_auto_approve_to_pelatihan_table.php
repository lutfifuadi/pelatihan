<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            if (!Schema::hasColumn('pelatihan', 'auto_approve')) {
                $table->boolean('auto_approve')->default(false)->after('kuota');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn('auto_approve');
        });
    }
};
