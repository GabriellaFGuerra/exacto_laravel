<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfractionsTable extends Migration
{
    public function up()
    {
        Schema::create('infractions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('type', 255)->nullable();
            $table->string('year', 4)->nullable();
            $table->string('city', 255)->nullable();
            $table->date('date')->nullable();
            $table->string('owner', 255)->nullable();
            $table->string('apt', 255)->nullable();
            $table->string('block', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('irregularity_description')->nullable();
            $table->string('subject', 255)->nullable();
            $table->longText('article_description')->nullable();
            $table->longText('notification_description')->nullable();
            $table->string('receipt', 255)->nullable();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('infractions');
    }
}