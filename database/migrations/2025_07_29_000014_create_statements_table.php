<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementsTable extends Migration
{
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->id('statement_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('reference', 8)->nullable();
            $table->date('send_date')->nullable();
            $table->string('sent_by', 255)->nullable();
            $table->text('observation')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('receipt', 255)->nullable();

            $table->foreign('client_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('statements');
    }
}