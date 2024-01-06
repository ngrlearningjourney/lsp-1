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
        Schema::create('file_bukus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_buku');
            $table->string('file');
            $table->tinyInteger('hapus_file_buku');
            $table->timestamps();
            $table->foreign('id_buku')
            ->references('id')
            ->on('bukus')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_bukus');
    }
};
