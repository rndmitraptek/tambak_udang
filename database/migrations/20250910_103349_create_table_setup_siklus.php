<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setup_siklus', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('lokasi_id')->index();
            $table->string('nama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status')->default('OPEN');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lokasi_id')->references('id')->on('setup_lokasi')->onDelete('cascade');
        });

        Schema::create('setup_siklus_petak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siklus_id')->index();
            $table->unsignedBigInteger('petak_id')->index();

            $table->foreign('siklus_id')->references('id')->on('setup_siklus')->onDelete('cascade');
            $table->foreign('petak_id')->references('id')->on('setup_petak')->onDelete('cascade');

            // kombinasi index untuk mempercepat pencarian unik
            $table->unique(['siklus_id', 'petak_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('setup_siklus');
        Schema::dropIfExists('setup_siklus_petak');
    }
};