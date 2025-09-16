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
        Schema::create('panen_detail', function (Blueprint $table) {
            $table->id('id_panen_detail');
            $table->integer('id_panen');
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique();
            $table->date('tanggal_panen');
            $table->integer('id_customer')->constrained('setup_customer');
            $table->string('customer',100);
            $table->integer('id_metode_pembayaran')->constrained('setup_metode_pembayaran');
            $table->string('metode_pembayaran',50);
            $table->string('item',100);
            $table->float('harga',8,2);
            $table->float('jumlah',8,2);
            $table->float('subtotal',8,2);
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
        Schema::dropIfExists('panen_detail');
    }
};
