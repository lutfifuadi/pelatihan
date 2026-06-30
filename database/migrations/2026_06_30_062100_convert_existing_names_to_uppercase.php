<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert names in users table
        DB::statement('UPDATE users SET name = UPPER(name) WHERE name IS NOT NULL');

        // Convert names in peserta_profiles table
        DB::statement('UPDATE peserta_profiles SET nama_lengkap = UPPER(nama_lengkap) WHERE nama_lengkap IS NOT NULL');

        // Convert names in kta_members table
        DB::statement('UPDATE kta_members SET nama_lengkap = UPPER(nama_lengkap) WHERE nama_lengkap IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed since converting to uppercase is a one-way normalization.
    }
};
