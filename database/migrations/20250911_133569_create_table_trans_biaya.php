<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_biaya', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal_transaksi');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->unsignedBigInteger('biaya_id');
            $table->decimal('nominal', 30, 2)->default(0);
            $table->unsignedBigInteger('coa_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamps();
            $table->timestamp('validated_at')->nullable();
            $table->softDeletes();

            // relasi ke tabel lain jika ada
            $table->foreign('coa_id')->references('id_coa')->on('setup_coa')->onDelete('cascade');
            $table->foreign('biaya_id')->references('id_biaya')->on('setup_biaya')->onDelete('cascade');
        });

        Schema::create('transaksi_biaya_siklus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_biaya_id');
            $table->unsignedBigInteger('siklus_id');
            $table->timestamps();

            // relasi ke tabel lain jika ada
            $table->foreign('trans_biaya_id')->references('id')->on('transaksi_biaya')->onDelete('cascade');
            $table->foreign('siklus_id')->references('id_siklus')->on('setup_siklus')->onDelete('cascade');
        });

        Schema::create('transaksi_biaya_petak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_biaya_id');
            $table->unsignedBigInteger('trans_biaya_siklus_id');
            $table->unsignedBigInteger('petak_id');
            $table->unsignedBigInteger('biaya_id');
            $table->double('luas')->nullable();
            $table->double('persentase')->nullable();
            $table->decimal('nominal_petak', 30, 2)->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            // relasi ke tabel lain jika ada
            $table->foreign('trans_biaya_id')->references('id')->on('transaksi_biaya')->onDelete('cascade');
            $table->foreign('trans_biaya_siklus_id')->references('id')->on('transaksi_biaya_siklus')->onDelete('cascade');
            $table->foreign('petak_id')->references('id_petak')->on('setup_petak')->onDelete('cascade');
            $table->foreign('biaya_id')->references('id_biaya')->on('setup_biaya')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_biaya');
        Schema::dropIfExists('transaksi_biaya_siklus');
        Schema::dropIfExists('transaksi_biaya_petak');
    }
};