<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_pakan', function (Blueprint $table) {
            $table->id('id_stok_pakan');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('pakan_id');
            $table->unsignedBigInteger('lokasi_id');
            $table->decimal('stok', 30, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pakan_id')->references('id_pakan')->on('setup_pakan')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('stok_pakan');
    }
};