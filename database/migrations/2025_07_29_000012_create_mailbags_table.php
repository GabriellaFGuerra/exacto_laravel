<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailbagsTable extends Migration
{
    public function up()
    {
        Schema::create('mailbags', function (Blueprint $table) {
            $table->id('mailbag_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('seal', 255)->nullable();
            $table->text('observation')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('electronic_pg', 255)->nullable();
            $table->string('electronic_pg2', 255)->nullable();

            $table->foreign('client_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mailbags');
    }
}