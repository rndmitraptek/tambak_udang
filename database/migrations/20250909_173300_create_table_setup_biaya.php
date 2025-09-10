<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setup_biaya', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->enum('kelompok', ['Gabungan', 'Perlokasi', 'Perpetak']);
            $table->boolean('periode')->default(false);
            $table->decimal('nominal', 15, 2)->nullable();
            $table->unsignedBigInteger('coa_id')->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coa_id')->references('id')->on('setup_coa')->onDelete('set null');
        });

        Schema::create('setup_biaya_lokasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biaya_id');
            $table->unsignedBigInteger('lokasi_id');

            $table->foreign('biaya_id')->references('id')->on('setup_biaya')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('setup_lokasi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('setup_biaya');
        Schema::dropIfExists('setup_biaya_lokasi');
    }
};