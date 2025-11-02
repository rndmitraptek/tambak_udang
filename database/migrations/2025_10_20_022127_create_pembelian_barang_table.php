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
        Schema::create('pembelian_barang', function (Blueprint $table) {
            $table->id('id_pembelian_barang');
            $table->uuid('uuid');
            $table->string('no_pembelian_barang',100);
            $table->date('tanggal_pembelian_barang');
            $table->date('tanggal_jatuh_tempo');
            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('setup_lokasi')->onDelete('restrict');
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')->references('id_supplier')->on('setup_supplier')->onDelete('restrict');
            $table->decimal('jumlah',30,2)->default(0);
            $table->decimal('total',30,2)->default(0);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('pembayaran',10)->default('TUNAI');
            $table->text('keterangan');
            $table->unsignedBigInteger('id_coa')->nullable();
            $table->foreign('id_coa')->references('id_coa')->on('setup_coa')->onDelete('restrict')->nullable();
            $table->string('kode_coa',30)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_barang');
    }
};
