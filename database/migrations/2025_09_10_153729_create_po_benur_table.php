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
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique();
            $table->string('no_po',100);
            $table->integer('id_supplier')->constrained('setup_supplier');
            $table->string('supplier',100);
            $table->date('tanggal_po');
            $table->date('tanggal_kirim')->nullable();
            $table->integer('id_lokasi')->constrained('setup_lokasi');
            $table->string('lokasi',100);
            $table->float('qty',8,2);
            $table->float('harga_satuan',8,2);
            $table->float('total',8,2);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('po_benur');
    }
};
