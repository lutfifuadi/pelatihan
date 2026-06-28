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
        Schema::create('schedule_instruktur', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_utama')->default(true);
            $table->timestamp('created_at')->nullable();

            // Foreign keys
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('schedules')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Unique constraint — tidak boleh duplikat (schedule_id, user_id)
            $table->unique(['schedule_id', 'user_id'], 'uq_schedule_instruktur');

            // Index untuk query top instruktur paling aktif
            $table->index('user_id', 'idx_schedule_instruktur_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_instruktur');
    }
};
