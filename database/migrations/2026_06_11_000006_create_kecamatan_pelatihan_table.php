<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatan_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['kecamatan_id', 'pelatihan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecamatan_pelatihan');
    }
};
