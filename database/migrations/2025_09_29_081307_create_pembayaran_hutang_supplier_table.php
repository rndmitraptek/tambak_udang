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
        Schema::create('pembayaran_hutang_supplier', function (Blueprint $table) {
            $table->id('id_pembayaran_hutang_supplier');
            $table->uuid('uuid');
            $table->string('no_faktur',100);
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')->references('id_supplier')->on('setup_supplier')->onDelete('restrict');
            $table->date('tanggal_bayar');
            $table->float('total_hutang',18,2);
            $table->float('total_piutang',18,2);
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
        Schema::dropIfExists('pembayaran_hutang_supplier');
    }
};
