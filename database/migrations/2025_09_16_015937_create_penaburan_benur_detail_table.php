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
        Schema::create('penaburan_benur_detail', function (Blueprint $table) {
            $table->id('id_penaburan_benur_detail');
            $table->uuid('uuid');
            $table->integer('id_penaburan_benur')->constrained('penaburan_benur');
            $table->integer('id_petak')->constrained('setup_petak');
            $table->string('nama_petak',100);
            $table->integer('id_benur')->constrained('setup_benur');
            $table->integer('siklus_id')->constrained('setup_benur');
            $table->string('kode_supplier',100);
            $table->string('jenis_benur',100);
            $table->float('harga_bruto',8,2);
            $table->float('jumlah_bruto',8,2);
            $table->float('subtotal_bruto',8,2);
            $table->float('harga_neto',8,2);
            $table->float('jumlah_neto',8,2);
            $table->float('subtotal_neto',8,2);
            $table->float('harga_actual',8,2);
            $table->float('jumlah_actual',8,2);
            $table->float('subtotal_actual',8,2);
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
        Schema::dropIfExists('penaburan_benur_detail');
    }
};
