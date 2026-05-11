<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_peringatan', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('restrict');
            $table->enum('status_sp', ['SP1', 'SP2', 'SP3']);
            $table->date('tanggal_terbit');
            $table->text('keterangan_sp')->nullable();
            $table->integer('total_poin_saat_sp');
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_peringatan');
    }
};
