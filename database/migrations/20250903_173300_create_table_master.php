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
            $table->id('id_lokasi');
            $table->uuid('uuid')->unique();
            $table->string('kode_lokasi')->unique();
            $table->string('nama_lokasi');
            $table->string('alamat_lokasi')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Blok/Area
        Schema::create('setup_blok', function (Blueprint $table) {
            $table->id('id_blok');
            $table->uuid('uuid')->unique();
            $table->foreignId('lokasi_id')->constrained('setup_lokasi')->onDelete('cascade');
            $table->string('nama_blok');
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Petak/Kolam
        Schema::create('setup_petak', function (Blueprint $table) {
            $table->id('id_petak');
            $table->uuid('uuid')->unique();
            $table->foreignId('lokasi_id')->constrained('setup_lokasi')->onDelete('cascade');
            $table->foreignId('blok_id')->constrained('setup_blok')->onDelete('cascade');
            $table->string('nama_petak');
            $table->double('luas_petak')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Supplier
        Schema::create('setup_supplier', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->uuid('uuid')->unique();
            $table->string('kode_supplier')->unique();
            $table->string('nama_supplier');
            $table->string('alamat_supplier')->nullable();
            $table->string('telepon_supplier')->nullable();
            $table->string('email_supplier')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('catatan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master Pakan
        Schema::create('setup_pakan', function (Blueprint $table) {
            $table->id('id_pakan');
            $table->uuid('uuid')->unique();
            $table->string('kode_pakan')->unique();
            $table->string('nama_pakan');
            $table->string('jenis_pakan')->nullable();
            $table->string('merk_pakan')->nullable();
            $table->string('satuan_pakan')->nullable();
            $table->double('harga_pakan')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master Benur
        Schema::create('setup_benur', function (Blueprint $table) {
            $table->id('id_benur');
            $table->uuid('uuid')->unique();
            $table->string('kode_benur')->unique();
            $table->string('kode_supplier');
            $table->string('jenis_benur');
            $table->double('harga_benur')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master Customer
        Schema::create('setup_customer', function (Blueprint $table) {
            $table->id('id_customer');
            $table->uuid('uuid')->unique();
            $table->string('kode_customer')->unique();
            $table->string('nama_customer');
            $table->string('alamat_customer')->nullable();
            $table->string('telepon_customer')->nullable();
            $table->string('email_customer')->nullable();
            $table->string('catatan')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Master COA
        Schema::create('setup_coa', function (Blueprint $table) {
            $table->id('id_coa');
            $table->uuid('uuid')->unique();
            $table->string('kode_coa')->unique();
            $table->string('nama_coa');
            $table->string('tipe_coa');
            $table->string('pos_laporan');
            $table->string('kode_parent')->nullable();
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