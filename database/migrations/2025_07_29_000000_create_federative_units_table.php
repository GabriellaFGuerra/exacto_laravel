<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFederativeUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('federative_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('abbreviation', 2)->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('federative_units');
    }
}