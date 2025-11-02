<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setup_barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->uuid('uuid');
            $table->string('nama_barang',100);
            $table->decimal('harga',30,2)->default(0);
            $table->unsignedBigInteger('id_coa')->nullable();
            $table->foreign('id_coa')->references('id_coa')->on('setup_coa')->onDelete('restrict')->nullable();
            $table->string('kode_coa',30)->nullable();
            $table->boolean('is_activa')->default(false);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_barang');
    }
};
