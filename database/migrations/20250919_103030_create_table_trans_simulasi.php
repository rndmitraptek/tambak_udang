<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_simulasi', function (Blueprint $table) {
            $table->id('id_simulasi');
            $table->uuid('uuid')->unique();
            $table->date('tanggal_simulasi');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('siklus_id');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            // relasi ke tabel lain jika ada
            $table->foreign('lokasi_id')->references('id_lokasi')->on('setup_lokasi')->onDelete('cascade');
            $table->foreign('siklus_id')->references('id_siklus')->on('setup_siklus')->onDelete('cascade');
        });

        Schema::create('transaksi_simulasi_biaya', function (Blueprint $table) {
            $table->id('id_simulasi_biaya');
            $table->unsignedBigInteger('trans_simulasi_id');
            $table->unsignedBigInteger('petak_id');
            $table->unsignedBigInteger('benur_id');
            $table->double('luas_petak')->nullable();
            $table->decimal('nominal_biaya', 30, 2)->default(0);
            $table->float('doc')->nullable();
            $table->string('jenis_benur')->nullable();
            $table->float('jumlah_benur')->nullable();
            $table->jsonb('detail_biaya_actual')->nullable();
            $table->jsonb('detail_biaya_simulasi')->nullable();
            $table->timestamps();

            // relasi ke tabel lain jika ada
            $table->foreign('trans_simulasi_id')->references('id_simulasi')->on('transaksi_simulasi')->onDelete('cascade');
            $table->foreign('petak_id')->references('id_petak')->on('setup_petak')->onDelete('cascade');
            $table->foreign('benur_id')->references('id_benur')->on('setup_benur')->onDelete('cascade');
        });

        Schema::create('transaksi_simulasi_pendapatan', function (Blueprint $table) {
            $table->id('id_simulasi_pendapatan');
            $table->unsignedBigInteger('trans_simulasi_id');
            $table->unsignedBigInteger('petak_id');
            $table->decimal('harga_per_kg', 30, 2)->default(0);
            $table->decimal('biomassa', 30, 2)->default(0);
            $table->decimal('pendapatan', 30, 2)->default(0);
            $table->decimal('pendapatan_actual_partial', 30, 2)->default(0);
            $table->timestamps();

            // relasi ke tabel lain jika ada
            $table->foreign('trans_simulasi_id')->references('id_simulasi')->on('transaksi_simulasi')->onDelete('cascade');
            $table->foreign('petak_id')->references('id_petak')->on('setup_petak')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_simulasi');
        Schema::dropIfExists('transaksi_simulasi_biaya');
        Schema::dropIfExists('transaksi_simulasi_pendapatan');
    }
};