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
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique();
            $table->integer('urut');
            $table->string('label');
            $table->string('icon');
            $table->string('route_link');
            $table->boolean('is_parent');
            $table->integer('id_menu_parent');
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
        Schema::dropIfExists('menu');
    }
};
