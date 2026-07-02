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
        Schema::create('pengumuman_pelatihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatihan_id')->nullable()->constrained('pelatihan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('konten');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            // Index pada pelatihan_id dan is_pinned
            $table->index('pelatihan_id');
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman_pelatihans');
    }
};
