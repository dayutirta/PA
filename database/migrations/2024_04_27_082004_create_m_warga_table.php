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
        Schema::create('m_warga', function (Blueprint $table) {
            $table->id('nik');
            $table->unsignedBigInteger('id_level')->index();
            $table->string('nokk',20)->unique();
            $table->string('nama',100);
            $table->string('jenis_kelamin',10);
            $table->string('tempat_lahir',100);
            $table->date('tanggal_lahir');
            $table->string('alamat',100);
            $table->string('agama',10);
            $table->string('status',10);
            $table->string('kewarganegaraan',50);
            $table->string('pekerjaan',50);
            $table->string('pendidikan',100);
            $table->string('status_pernikahan',100);
            $table->timestamps();

            $table->foreign('id_level')->references('id_level')->on('m_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_warga');
    }
};
