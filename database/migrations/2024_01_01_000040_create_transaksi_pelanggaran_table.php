<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pelanggaran', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('restrict');
            $table->foreignId('id_jenis')->constrained('jenis_pelanggaran', 'id_jenis')->onDelete('restrict');
            $table->foreignId('id_user_pelapor')->constrained('users')->onDelete('restrict');
            $table->date('tanggal_kejadian');
            $table->time('waktu_kejadian')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('saksi')->nullable();
            $table->enum('status_penanganan', ['belum', 'proses', 'selesai'])->default('belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pelanggaran');
    }
};
