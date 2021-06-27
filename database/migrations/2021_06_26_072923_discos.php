<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Discos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discos', function(Blueprint $table) {
            $table->id();
            $table->foreignId('artistas_id')->constrained('artistas');
            $table->string('nombre', 200);
            $table->string('fecha_lanzamiento', 100);
            $table->string('portada', 400);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
