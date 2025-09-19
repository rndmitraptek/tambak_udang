<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setup_biaya', function (Blueprint $table) {
            $table->id('id_biaya');
            $table->uuid('uuid')->unique();
            $table->string('kode_biaya')->unique();
            $table->string('nama_biaya');
            $table->enum('kelompok_biaya', ['Gabungan', 'Perlokasi', 'Perpetak']);
            $table->boolean('periode_biaya')->default(false);
            $table->decimal('nominal_biaya', 15, 2)->nullable();
            $table->unsignedBigInteger('coa_id')->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coa_id')->references('id_coa')->on('setup_coa')->onDelete('set null');
        });

        Schema::create('setup_biaya_lokasi', function (Blueprint $table) {
            $table->id('id_biaya_lokasi');
            $table->unsignedBigInteger('biaya_id');
            $table->unsignedBigInteger('lokasi_id');

            $table->foreign('biaya_id')->references('id_biaya')->on('setup_biaya')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('setup_biaya');
        Schema::dropIfExists('setup_biaya_lokasi');
    }
};