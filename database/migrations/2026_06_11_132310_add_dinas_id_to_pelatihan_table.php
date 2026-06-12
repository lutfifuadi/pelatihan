<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->foreignId('dinas_id')->nullable()->constrained('dinas')->onDelete('set null')->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropForeign(['dinas_id']);
            $table->dropColumn('dinas_id');
        });
    }
};
