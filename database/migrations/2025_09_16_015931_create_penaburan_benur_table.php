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
        Schema::create('penaburan_benur', function (Blueprint $table) {
            $table->id('id_penaburan_benur');
            $table->uuid('uuid');
            $table->date('tanggal_penaburan');
            $table->string('no_penaburan_benur',100)->unique();
            $table->unsignedBigInteger('id_po_benur');
            $table->foreign('id_po_benur')->references('id_po_benur')->on('po_benur')->onDelete('restrict');
            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('setup_lokasi')->onDelete('restrict');
            $table->unsignedBigInteger('id_siklus');
            $table->foreign('id_siklus')->references('id_siklus')->on('setup_siklus')->onDelete('restrict');
            $table->text('keterangan')->nullable();
            $table->float('jumlah_bruto',8,2);
            $table->float('total_nominal_bruto',8,2);
            $table->float('jumlah_netto',8,2);
            $table->float('total_nominal_netto',8,2);
            $table->float('jumlah_actual',8,2);
            $table->float('total_nominal_actual',8,2);
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
        Schema::dropIfExists('penaburan_benur');
    }
};
