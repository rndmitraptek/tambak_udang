<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('panen_detail', function (Blueprint $table) {
            $table->id('id_panen_detail');
            $table->integer('id_panen');
            $table->uuid('uuid');
            $table->date('tanggal_panen');
            $table->unsignedBigInteger('id_customer');
            $table->foreign('id_customer')->references('id_customer')->on('setup_customer')->onDelete('restrict');
            $table->unsignedBigInteger('id_payment_method');
            $table->foreign('id_payment_method')->references('id_payment_method')->on('setup_payment_method')->onDelete('restrict');
            $table->unsignedBigInteger('id_item');
            $table->foreign('id_item')->references('id_item')->on('setup_item')->onDelete('restrict');
            $table->float('harga',8,2);
            $table->float('jumlah',8,2);
            $table->float('subtotal',8,2);
            $table->unsignedBigInteger('id_coa')->nullable();
            $table->foreign('id_coa')->references('id_coa')->on('setup_coa')->onDelete('restrict');
            $table->string('kode_coa',30)->nullable();
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
        Schema::dropIfExists('panen_detail');
    }
};
