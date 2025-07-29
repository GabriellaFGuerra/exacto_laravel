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
            $table->string('zip_code', 15)->nullable();
            $table->timestamps();

            $table->foreign('uf_id')->references('uf_id')->on('federative_units')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('municipalities');
    }
}