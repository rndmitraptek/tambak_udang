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
        Schema::create('pembayaran_hutang_supplier_detail_piutang', function (Blueprint $table) {
            $table->id('id_pembayaran_hutang_supplier_detail_piutang');
            $table->unsignedBigInteger('id_pembayaran_hutang_supplier');
            $table->foreign('id_pembayaran_hutang_supplier')->references('id_pembayaran_hutang_supplier')->on('pembayaran_hutang_supplier')->onDelete('restrict');
            $table->unsignedBigInteger('id_piutang_supplier');
            $table->foreign('id_piutang_supplier')->references('id_piutang_supplier')->on('piutang_supplier')->onDelete('restrict');
            $table->float('nominal_piutang',18,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutang_supplier_detail_piutang');
    }
};
