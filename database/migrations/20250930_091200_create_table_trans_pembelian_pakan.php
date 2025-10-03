<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembelian_pakan', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->uuid('uuid')->unique();
            $table->string('no_pembelian')->unique();
            $table->date('tanggal_pembelian');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('siklus_id');
            $table->integer('jumlah_item');
            $table->decimal('total', 30, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')->references('id_supplier')->on('setup_supplier')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
            $table->foreign('siklus_id')->references('id_siklus')->on('setup_siklus')->onDelete('cascade');
        });

        Schema::create('pembelian_pakan_detail', function (Blueprint $table) {
            $table->id('id_pembelian_detail');
            $table->unsignedBigInteger('id_pembelian');
            $table->unsignedBigInteger('id_pakan');
            $table->decimal('harga', 30, 2);
            $table->integer('jumlah');
            $table->decimal('subtotal', 30, 2);
            $table->timestamps();

            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian_pakan')->onDelete('cascade');
            $table->foreign('id_pakan')->references('id_pakan')->on('setup_pakan')->onDelete('cascade');
        });

        Schema::create('history_kartu_stok', function (Blueprint $table) {
            $table->id('id_kartu');
            $table->date('tanggal');
            $table->integer('tahun');
            $table->unsignedBigInteger('id_pakan')->nullable();
            $table->unsignedBigInteger('id_lokasi')->nullable();
            $table->string('transaksi'); // pembelian / pemakaian dll
            $table->decimal('awal', 30,2)->default(0);
            $table->decimal('masuk', 30,2)->default(0);
            $table->decimal('keluar', 30,2)->default(0);
            $table->decimal('saldo', 30,2)->default(0);
            $table->string('referensi_no')->nullable(); // no pembelian dll
            $table->unsignedBigInteger('referensi_id')->nullable(); // id pembelian dll
            $table->timestamps();

            $table->foreign('id_pakan')->references('id_pakan')->on('setup_pakan')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembelian_pakan');
        Schema::dropIfExists('pembelian_pakan_detail');
        Schema::dropIfExists('history_kartu_stok');
    }
};