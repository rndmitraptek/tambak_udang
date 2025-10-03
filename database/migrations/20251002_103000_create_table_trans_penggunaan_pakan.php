<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penggunaan_pakan', function (Blueprint $table) {
            $table->id('id_penggunaan');
            $table->uuid('uuid')->unique();
            $table->string('no_penggunaan')->unique();
            $table->date('tanggal_penggunaan');
            $table->string('waktu');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('siklus_id');
            $table->integer('jumlah_petak');
            $table->decimal('total', 30, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
            $table->foreign('siklus_id')->references('id_siklus')->on('setup_siklus')->onDelete('cascade');
        });

        Schema::create('penggunaan_pakan_detail', function (Blueprint $table) {
            $table->id('id_penggunaan_detail');
            $table->unsignedBigInteger('id_penggunaan');
            $table->unsignedBigInteger('pakan_id');
            $table->unsignedBigInteger('petak_id');
            $table->decimal('jumlah',30,2);
            $table->decimal('harga_per_kg',30,2);
            $table->decimal('subtotal',30,2);
            $table->timestamps();

            $table->foreign('id_penggunaan')->references('id_penggunaan')->on('penggunaan_pakan')->onDelete('cascade');
            $table->foreign('pakan_id')->references('id_pakan')->on('setup_pakan')->onDelete('cascade');
            $table->foreign('petak_id')->references('id_petak')->on('setup_petak')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('penggunaan_pakan');
        Schema::dropIfExists('penggunaan_pakan_detail');
    }
};