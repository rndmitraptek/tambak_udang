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
        Schema::create('jurnal_detail', function (Blueprint $table) {
            $table->id('id_jurnal_detail');
            $table->unsignedBigInteger('id_jurnal');
            $table->foreign('id_jurnal')->references('id_jurnal')->on('jurnal')->onDelete('restrict');
            $table->unsignedBigInteger('id_coa');
            $table->foreign('id_coa')->references('id_coa')->on('setup_coa')->onDelete('restrict');
            $table->string('kode_coa',20);
            $table->string('nama_coa',200);
            $table->decimal('debit',30,2)->default(0);
            $table->decimal('kredit',30,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_detail');
    }
};
