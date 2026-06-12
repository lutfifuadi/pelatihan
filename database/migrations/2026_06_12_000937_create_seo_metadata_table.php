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
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation — bisa ditempel ke model mana pun
            $table->morphs('seoable');  // otomatis buat seoable_id + seoable_type

            // Core Meta Tags
            $table->string('meta_title', 70)->nullable();        // Title tag (max 70 char)
            $table->string('meta_description', 180)->nullable();  // Meta description (max 180 char)
            $table->string('meta_keywords', 255)->nullable();      // Keywords (comma separated)

            // Open Graph
            $table->string('og_title', 70)->nullable();
            $table->string('og_description', 180)->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('og_type', 50)->nullable()->default('website');

            // Twitter Card
            $table->string('twitter_title', 70)->nullable();
            $table->string('twitter_description', 180)->nullable();
            $table->string('twitter_image', 500)->nullable();

            // Canonical & Robots Control
            $table->string('canonical_url', 500)->nullable();
            $table->string('robots', 50)->nullable()->default('index, follow');

            // Priority untuk sitemap
            $table->decimal('sitemap_priority', 2, 1)->nullable()->default(0.8);
            $table->string('sitemap_changefreq', 20)->nullable()->default('weekly');

            // Structured data JSON (untuk custom schema per halaman)
            $table->json('structured_data')->nullable();

            $table->timestamps();

            // Index untuk performa query polymorphic
            $table->index(['seoable_id', 'seoable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_metadata');
    }
};
