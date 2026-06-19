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
        Schema::create('form_field_configs', function (Blueprint $table) {
            $table->id();
            $table->string('section', 50);
            $table->string('field_key', 100);
            $table->string('label', 255);
            $table->string('placeholder', 255)->nullable();
            $table->string('type', 50)->default('text');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->string('width', 20)->default('half');
            $table->string('options_group', 50)->nullable();
            $table->string('validation_rules', 255)->nullable();
            $table->json('show_if')->nullable();
            $table->timestamps();

            // Unique constraint composite: section + field_key
            $table->unique(['section', 'field_key']);

            // Index untuk query by section
            $table->index(['section', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_field_configs');
    }
};
