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
        Schema::create('setup_supplier_bank', function (Blueprint $table) {
            $table->id('id_setup_supplier_bank');
            $table->unsignedBigInteger('id_supplier');
            $table->foreign('id_supplier')->references('id_supplier')->on('setup_supplier')->onDelete('restrict');
            $table->string('no_rekening');
            $table->string('nama_bank',100);
            $table->string('atas_nama',100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_supplier_bank');
    }
};
