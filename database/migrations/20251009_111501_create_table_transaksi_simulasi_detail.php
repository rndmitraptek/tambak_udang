<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_simulasi_detail', function (Blueprint $table) {
            $table->id('id_simulasi_detail');
            $table->unsignedBigInteger('id_simulasi');
            $table->date('tanggal_simulasi');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('siklus_id');
            $table->unsignedBigInteger('petak_id');
            $table->decimal('biomassa', 30, 2)->default(0);
            $table->decimal('harga_per_kg', 30, 2)->default(0);
            $table->decimal('total_pendapatan', 30, 2)->default(0);
            $table->decimal('total_biaya', 30, 2)->default(0);
            $table->decimal('laba_rugi', 30, 2)->default(0);
            $table->decimal('hpp_per_kg', 30, 2)->default(0);
            $table->decimal('fcr', 30, 2)->default(0);
            $table->float('doc')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_simulasi')->references('id_simulasi')->on('transaksi_simulasi');
            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
            $table->foreign('siklus_id')->references('id_siklus')->on('setup_siklus')->onDelete('cascade');
            $table->foreign('petak_id')->references('id_petak')->on('setup_petak')->onDelete('cascade');
        });


    }

    public function down()
    {
        Schema::dropIfExists('transaksi_simulasi_detail');
    }
};