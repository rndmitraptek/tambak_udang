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
        Schema::create('pembayaran_piutang_customer_tunai', function (Blueprint $table) {
            $table->id('id_pembayaran_piutang_customer_tunai');
            $table->unsignedBigInteger('id_pembayaran_piutang_customer');
            $table->foreign('id_pembayaran_piutang_customer')->references('id_pembayaran_piutang_customer')->on('pembayaran_piutang_customer')->onDelete('restrict');
            $table->date('tanggal_bayar');
            $table->string('nama_penerima');
            $table->string('nama_pemberi');
            $table->float('nominal',18,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutang_customer_tunai');
    }
};
