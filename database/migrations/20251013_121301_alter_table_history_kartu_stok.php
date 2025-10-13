<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('history_kartu_stok', function (Blueprint $table) {
            $table->decimal('nominal_awal', 30, 2)->nullable();
            $table->decimal('nominal_masuk', 30, 2)->nullable();
            $table->decimal('nominal_keluar', 30, 2)->nullable();
            $table->decimal('nominal_akhir', 30, 2)->nullable();
        });


    }

    public function down()
    {
        Schema::table('history_kartu_stok', function (Blueprint $table) {
            $table->dropColumn([
                'nominal_awal',
                'nominal_masuk',
                'nominal_keluar',
                'nominal_akhir',
            ]);
        });
    }
};