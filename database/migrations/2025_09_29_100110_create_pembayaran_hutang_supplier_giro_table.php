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
        Schema::create('pembayaran_hutang_supplier_giro', function (Blueprint $table) {
            $table->id('pembayaran_hutang_supplier_giro');
            $table->unsignedBigInteger('id_pembayaran_hutang_supplier');
            $table->foreign('id_pembayaran_hutang_supplier')->references('id_pembayaran_hutang_supplier')->on('pembayaran_hutang_supplier')->onDelete('restrict');
            $table->unsignedBigInteger('id_rekening_bank');
            $table->foreign('id_rekening_bank')->references('id_rekening_bank')->on('setup_rekening_bank')->onDelete('restrict');
            $table->string('no_giro',100);
            $table->date('tanggal_terima_giro');
            $table->date('jatuh_tempo');
            $table->float('nominal',18,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutang_supplier_giro');
    }
};
