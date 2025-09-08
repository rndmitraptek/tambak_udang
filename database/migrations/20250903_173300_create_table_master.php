<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Lokasi
        Schema::create('setup_lokasi', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Blok/Area
        Schema::create('setup_blok', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('lokasi_id')->constrained('setup_lokasi')->onDelete('cascade');
            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Petak/Kolam
        Schema::create('setup_petak', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('lokasi_id')->constrained('setup_lokasi')->onDelete('cascade');
            $table->foreignId('blok_id')->constrained('setup_blok')->onDelete('cascade');
            $table->string('nama');
            $table->double('luas')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Supplier
        Schema::create('setup_supplier', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('catatan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master Pakan
        Schema::create('setup_pakan', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('jenis')->nullable();
            $table->string('merk')->nullable();
            $table->string('satuan')->nullable();
            $table->double('harga')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master Benur
        Schema::create('setup_benur', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('kode_supplier');
            $table->string('jenis');
            $table->double('harga')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master Customer
        Schema::create('setup_customer', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('catatan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master COA
        Schema::create('setup_coa', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('tipe');
            $table->string('pos_laporan');
            $table->string('kode_parent');
            $table->string('saldo_normal');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setup_coa');
        Schema::dropIfExists('setup_benur');
        Schema::dropIfExists('setup_pakan');
        Schema::dropIfExists('setup_supplier');
        Schema::dropIfExists('setup_petak');
        Schema::dropIfExists('setup_blok');
        Schema::dropIfExists('setup_customer');
    }
};