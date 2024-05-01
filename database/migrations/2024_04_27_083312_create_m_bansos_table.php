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
        Schema::create('m_bansos', function (Blueprint $table) {
            $table->id('id_bansos');
            $table->unsignedBigInteger('id_bantuan')->index();
            $table->string('nama_program',100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->integer('jumlah_penerima');
            $table->integer('anggaran');
            $table->string('lokasi',100);
            $table->timestamps();

            $table->foreign('id_bantuan')->references('id_bantuan')->on('m_bantuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_bansos');
    }
};
