<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppealsTable extends Migration
{
    public function up()
    {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('infraction_id')->nullable();
            $table->string('subject', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('appeal', 255)->nullable();
            $table->string('status', 20)->nullable();

            $table->foreign('infraction_id')->references('id')->on('infractions')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appeals');
    }
}