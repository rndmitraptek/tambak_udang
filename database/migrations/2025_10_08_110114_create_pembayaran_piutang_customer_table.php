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
        Schema::create('pembayaran_piutang_customer', function (Blueprint $table) {
            $table->id('id_pembayaran_piutang_customer');
            $table->uuid('uuid');
            $table->string('no_faktur',100);
             $table->unsignedBigInteger('id_customer');
            $table->foreign('id_customer')->references('id_customer')->on('setup_customer')->onDelete('restrict');
            $table->date('tanggal_bayar');
            $table->float('total_bayar',18,2);
            $table->text('keterangan')->nullable();
            $table->string('status',10)->default('DRAFT');
            $table->string('file',200)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutang_customer');
    }
};
