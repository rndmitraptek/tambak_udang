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
        Schema::create('pembayaran_piutang_customer_transfer', function (Blueprint $table) {
            $table->id('id_pembayaran_piutang_customer_transfer');
            $table->unsignedBigInteger('id_pembayaran_piutang_customer');
            $table->foreign('id_pembayaran_piutang_customer')->references('id_pembayaran_piutang_customer')->on('pembayaran_piutang_customer')->onDelete('restrict');
            $table->unsignedBigInteger('id_rekening_bank');
            $table->foreign('id_rekening_bank')->references('id_rekening_bank')->on('setup_rekening_bank')->onDelete('restrict');
            $table->string('bank_pengirim',50);
            $table->string('atas_nama_pengirim',50);
            $table->string('no_rekening_pengirim',50);
            $table->boolean('is_biaya_transfer')->default(false);
            $table->float('biaya_transfer',18,2)->default(0);
            $table->float('nominal',18,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutang_customer_transfer');
    }
};
