<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('login', 60)->unique()->nullable();
            $table->string('password', 255);
            $table->enum('user_type', ['admin', 'customer']);
            $table->boolean('status')->default(true);
            $table->tinyInteger('notification')->default(0);
            $table->string('address', 255)->nullable();
            $table->string('number', 20)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood', 255)->nullable();
            $table->unsignedBigInteger('municipality_id')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('cnpj', 20)->unique()->nullable();
            $table->string('cpf', 20)->unique()->nullable();
            $table->string('photo', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('municipality_id')->references('id')->on('municipalities')->onDelete('set null');

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}