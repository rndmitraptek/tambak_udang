<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retur_pakan', function (Blueprint $table) {
            $table->id('id_retur');
            $table->uuid('uuid')->unique();
            $table->string('no_retur')->unique();
            $table->date('tanggal_retur');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('siklus_id');
            $table->unsignedBigInteger('pembelian_id');
            $table->decimal('total', 30, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
            $table->foreign('siklus_id')->references('id_siklus')->on('setup_siklus')->onDelete('cascade');
            $table->foreign('pembelian_id')->references('id_pembelian')->on('pembelian_pakan')->onDelete('cascade');
        });

        Schema::create('retur_pakan_detail', function (Blueprint $table) {
            $table->id('id_retur_detail');
            $table->unsignedBigInteger('id_retur');
            $table->unsignedBigInteger('pakan_id');
            $table->decimal('jumlah_retur',30,2);
            $table->decimal('harga_per_kg',30,2);
            $table->decimal('subtotal',30,2);
            $table->timestamps();

            $table->foreign('id_retur')->references('id_retur')->on('retur_pakan')->onDelete('cascade');
            $table->foreign('pakan_id')->references('id_pakan')->on('setup_pakan')->onDelete('cascade');
            
        });

    }

    public function down()
    {
        Schema::dropIfExists('retur_pakan');
        Schema::dropIfExists('retur_pakan_detail');
    }
};