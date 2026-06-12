<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert existing string data to valid JSON array format
        $profiles = DB::table('peserta_profiles')
            ->whereNotNull('link_medsos')
            ->where('link_medsos', '!=', '')
            ->get();

        foreach ($profiles as $profile) {
            if (!json_validate($profile->link_medsos)) {
                DB::table('peserta_profiles')
                    ->where('id', $profile->id)
                    ->update([
                        'link_medsos' => json_encode([
                            ['platform' => 'Lainnya', 'url' => $profile->link_medsos]
                        ])
                    ]);
            }
        }

        Schema::table('peserta_profiles', function (Blueprint $table) {
            $table->json('link_medsos')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('peserta_profiles', function (Blueprint $table) {
            $table->string('link_medsos')->nullable()->change();
        });
    }
};
