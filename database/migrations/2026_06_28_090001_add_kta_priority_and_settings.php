<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom flag prioritas KTA di tabel enrollments,
     * memastikan index pada kolom nik di tabel kta_members,
     * dan menambahkan pengaturan mode verifikasi KTA otomatis.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom is_kta_priority di tabel enrollments
        Schema::table('enrollments', function (Blueprint $table) {
            $table->boolean('is_kta_priority')->default(false)->after('status');
        });

        // 2. Pastikan tabel kta_members memiliki index pada kolom nik
        $this->ensureKtaMembersNikIndex();

        // 3. Tambahkan record setting kta_verification_mode jika belum ada
        DB::table('settings')->insertOrIgnore([
            'key' => 'kta_verification_mode',
            'value' => 'off',
            'group' => 'general',
            'label' => 'Mode Verifikasi KTA Otomatis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus setting
        DB::table('settings')->where('key', 'kta_verification_mode')->delete();

        // Hapus index nik jika sebelumnya ditambahkan oleh migrasi ini
        $this->dropKtaMembersNikIndexIfManaged();

        // Hapus kolom is_kta_priority
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('is_kta_priority');
        });
    }

    /**
     * Memastikan index pada kolom nik di tabel kta_members.
     * Jika sudah terdapat index non-PRIMARY di kolom nik, tidak melakukan apa-apa.
     */
    private function ensureKtaMembersNikIndex(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $hasIndex = false;
            $indexes = DB::select("PRAGMA index_list('kta_members')");
            foreach ($indexes as $index) {
                $index = (array) $index;
                $name = $index['name'] ?? '';
                $origin = $index['origin'] ?? '';
                if ($origin === 'pk' || stripos($name, 'primary') !== false) {
                    continue;
                }

                $info = DB::select("PRAGMA index_info('{$name}')");
                foreach ($info as $col) {
                    $col = (array) $col;
                    $colName = $col['name'] ?? '';
                    if ($colName === 'nik') {
                        $hasIndex = true;
                        break 2;
                    }
                }
            }

            if ($hasIndex) {
                return;
            }
        } else {
            $existingIndexes = DB::select(
                "SHOW INDEX FROM kta_members WHERE Column_name = 'nik' AND Key_name != 'PRIMARY'"
            );

            if (! empty($existingIndexes)) {
                return;
            }
        }

        Schema::table('kta_members', function (Blueprint $table) {
            $table->index('nik', 'idx_kta_members_nik');
        });
    }

    /**
     * Menghapus index nik yang ditambahkan oleh migrasi ini.
     * Index asli dari constraint unique tidak dihapus untuk menghindari
     * gangguan terhadap integritas data.
     */
    private function dropKtaMembersNikIndexIfManaged(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $managedIndex = DB::select(
                "SELECT name FROM sqlite_master WHERE type = 'index' AND tbl_name = 'kta_members' AND name = 'idx_kta_members_nik'"
            );
        } else {
            $managedIndex = DB::select(
                "SHOW INDEX FROM kta_members WHERE Key_name = 'idx_kta_members_nik'"
            );
        }

        if (empty($managedIndex)) {
            return;
        }

        Schema::table('kta_members', function (Blueprint $table) {
            $table->dropIndex('idx_kta_members_nik');
        });
    }
};
