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
        Schema::create('piutang_supplier', function (Blueprint $table) {
            $table->id('id_piutang_supplier');
            $table->uuid('uuid');
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')->references('id_supplier')->on('setup_supplier')->onDelete('restrict');
            $table->string('no_faktur');
            $table->integer('reff_id');
            $table->string('reff_trans');
            $table->date('tanggal_piutang');
            $table->date('tanggal_jatuh_tempo')->nullable();;
            $table->float('jumlah_piutang',18,2);
            $table->float('dibayar',18,2);
            $table->float('sisa',18,2);
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
        Schema::dropIfExists('piutang_supplier');
    }
};
