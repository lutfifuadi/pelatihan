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
        Schema::create('kta_members', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique()->index();
            $table->string('nama_lengkap');
            $table->enum('status_kta', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('wilayah')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kta_members');
    }
};
