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
        Schema::create('panen', function (Blueprint $table) {
            $table->id('id_panen');
            $table->uuid('uuid');
            $table->string('no_panen',100);
            $table->date('tanggal_panen');
            $table->unsignedBigInteger('id_siklus');
            $table->foreign('id_siklus')->references('id_siklus')->on('setup_siklus')->onDelete('restrict');
            $table->unsignedBigInteger('id_petak');
            $table->foreign('id_petak')->references('id_petak')->on('setup_petak')->onDelete('restrict');
            $table->string('jenis_panen',20);
            $table->text('keterangan')->nullable();
            $table->float('jumlah',8,2);
            $table->float('total',8,2);
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
        Schema::dropIfExists('panen');
    }
};
