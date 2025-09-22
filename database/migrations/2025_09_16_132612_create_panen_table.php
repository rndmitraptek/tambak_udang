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
            $table->integer('id_siklus')->constrained('setup_siklus');
            $table->string('siklus',100);
            $table->integer('id_lokas')->constrained('setup_lokasi');
            $table->string('lokasi',100);
            $table->integer('id_blok')->constrained('setup_blok');
            $table->string('blok',100);
            $table->integer('id_petak')->constrained('setup_petak');
            $table->string('petak',100);
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
