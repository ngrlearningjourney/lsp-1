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
        Schema::create('transaksi_bukus', function (Blueprint $table) {
            $table->unsignedInteger('id_transaksi');
            $table->unsignedInteger('id_buku');
            $table->date('tanggal_awal_peminjaman');
            $table->date('tanggal_akhir_peminjaman');
            $table->foreign('id_transaksi')
                ->references('id')
                ->on('transaksis')
                ->onDelete('cascade');
            $table->foreign('id_buku')
                ->references('id')
                ->on('bukus')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_bukus');
    }
};
