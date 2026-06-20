<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom kodepos ke tabel kelurahans.
     */
    public function up(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            if (!Schema::hasColumn('kelurahans', 'kodepos')) {
                $table->string('kodepos', 5)->nullable()->after('kecamatan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            if (Schema::hasColumn('kelurahans', 'kodepos')) {
                $table->dropColumn('kodepos');
            }
        });
    }
};
