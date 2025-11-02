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
        Schema::create('setup_rekening_bank', function (Blueprint $table) {
            $table->id('id_rekening_bank');
            $table->string('uuid');
            $table->string('no_rekening');
            $table->string('nama_bank',100);
            $table->string('atas_nama',100);
            $table->unsignedBigInteger('id_coa');
            $table->foreign('id_coa')->references('id_coa')->on('setup_coa')->onDelete('restrict');
            $table->string('kode_coa',30);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_rekening_bank');
    }
};
