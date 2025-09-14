<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesTable extends Migration
{
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->unsignedBigInteger('federative_unit_id');
            $table->string('code')->nullable();
            $table->timestamps();

            $table->foreign('federative_unit_id')->references('id')->on('federative_units')->onDelete('cascade');
            $table->unique(['name', 'federative_unit_id']); // Evita cidades duplicadas no mesmo estado
        });
    }

    public function down()
    {
        Schema::dropIfExists('municipalities');
    }
}