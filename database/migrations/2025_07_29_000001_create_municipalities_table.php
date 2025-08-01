<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesTable extends Migration
{
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id('municipality_id');
            $table->string('name', 200);
            $table->unsignedBigInteger('uf_id');
            $table->timestamps();

            $table->foreign('uf_id')->references('uf_id')->on('federative_units')->onDelete('cascade');
            $table->unique(['name', 'uf_id']); // Evita cidades duplicadas no mesmo estado
        });
    }

    public function down()
    {
        Schema::dropIfExists('municipalities');
    }
}