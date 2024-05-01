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
        Schema::create('m_penerima', function (Blueprint $table) {
            $table->id('id_penerima');
            $table->unsignedBigInteger('id_bansos')->index();
            $table->string('nokk',20)->index();
            $table->string('nama',100);
            $table->integer('usia');
            $table->integer('pendapatan');
            $table->string('status_kesehatan',100);
            $table->string('pekerjaan',100);
            $table->string('notelp',100);
            $table->timestamps();

            $table->foreign('id_bansos')->references('id_bansos')->on('m_bansos');
            $table->foreign('nokk')->references('nokk')->on('m_warga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_penerima');
    }
};
