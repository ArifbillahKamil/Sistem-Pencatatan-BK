<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SQLite (used in tests) does not support ALTER COLUMN / ENUM.
     * We skip the ALTER on SQLite — the initial users migration already
     * uses a plain string column, so 'guru_wali' values are accepted.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru_bk','wali_kelas','guru_wali') NOT NULL DEFAULT 'guru_bk'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru_bk','wali_kelas') NOT NULL DEFAULT 'guru_bk'");
        }
    }
};
