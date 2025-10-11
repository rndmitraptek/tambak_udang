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
        Schema::create('pembayaran_piutang_customer_giro', function (Blueprint $table) {
            $table->id('id_pembayaran_piutang_customer_giro');
            $table->unsignedBigInteger('id_pembayaran_piutang_customer');
            $table->foreign('id_pembayaran_piutang_customer')->references('id_pembayaran_piutang_customer')->on('pembayaran_piutang_customer')->onDelete('restrict');
            $table->unsignedBigInteger('id_rekening_bank')->nullable();
            $table->foreign('id_rekening_bank')->references('id_rekening_bank')->on('setup_rekening_bank')->onDelete('restrict');
            $table->string('no_giro',100);
            $table->date('terima_giro');
            $table->date('jatuh_tempo');
            $table->float('nominal',18,2);
            $table->float('biaya_materai',18,2);
            $table->float('nominal_materai',18,2);
            $table->float('selisih_bayar',18,2);
            $table->boolean('is_biaya_materai')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutang_customer_giro');
    }
};
