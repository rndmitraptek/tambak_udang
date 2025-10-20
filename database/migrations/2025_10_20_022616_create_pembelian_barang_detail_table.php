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
        Schema::create('pembelian_barang_detail', function (Blueprint $table) {
            $table->id('id_pembelian_barang_detail');
            $table->unsignedBigInteger('id_pembelian_barang');
            $table->foreign('id_pembelian_barang')->references('id_pembelian_barang')->on('pembelian_barang')->onDelete('restrict');
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('setup_barang')->onDelete('restrict');
            $table->decimal('harga',30,2)->default(0);
            $table->decimal('qty',30,2)->default(0);
            $table->decimal('subtotal',30,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_barang_detail');
    }
};
