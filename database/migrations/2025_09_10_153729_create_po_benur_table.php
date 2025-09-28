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
        Schema::create('po_benur', function (Blueprint $table) {
            $table->id('id_po_benur');
            $table->uuid('uuid')->unique();
            $table->string('no_po',100);
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')->references('id_supplier')->on('setup_supplier')->onDelete('restrict');
            $table->date('tanggal_po');
            $table->date('tanggal_kirim')->nullable();
            $table->unsignedBigInteger('id_siklus');
            $table->foreign('id_siklus')->references('id_siklus')->on('setup_siklus')->onDelete('restrict');
            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('setup_lokasi')->onDelete('restrict');
            $table->float('qty',8,2);
            $table->float('harga_satuan',8,2);
            $table->float('total',8,2);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('po_benur');
    }
};
