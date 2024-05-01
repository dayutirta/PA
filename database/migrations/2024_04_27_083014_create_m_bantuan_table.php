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
        Schema::create('m_bantuan', function (Blueprint $table) {
            $table->id('id_bantuan');
            $table->string('kode_bantuan',10)->unique();
            $table->string('nama_bantuan',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_bantuan');
    }
};
