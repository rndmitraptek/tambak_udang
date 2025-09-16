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
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique();
            $table->date('tanggal_penaburan');
            $table->string('no_penaburan_benur',100)->unique();
            $table->integer('id_po_benur')->constrained('po_benur');
            $table->string('no_po',100);
            $table->string('supplier',100);
            $table->string('lokasi',100);
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
        Schema::dropIfExists('penaburan_benur');
    }
};
