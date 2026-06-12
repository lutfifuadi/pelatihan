<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_profiles', function (Blueprint $table) {
            $table->foreignId('pelatihan_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('pelatihan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_profiles', function (Blueprint $table) {
            $table->dropForeign(['pelatihan_id']);
            $table->dropColumn('pelatihan_id');
        });
    }
};
