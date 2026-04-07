<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoPesosTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_pesos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');       // Bulto, Kilo vivo, Kilo gancho, Remate
            $table->string('descripcion')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_pesos');
    }
}
