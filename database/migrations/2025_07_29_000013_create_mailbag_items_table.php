<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailbagItemsTable extends Migration
{
    public function up()
    {
        Schema::create('mailbag_items', function (Blueprint $table) {
            $table->id('mailbag_item_id');
            $table->unsignedBigInteger('mailbag_id')->nullable();
            $table->string('provider', 200)->nullable();
            $table->string('document_type', 200)->nullable();
            $table->string('check_number', 50)->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->date('expiration_date')->nullable();
            $table->tinyInteger('closed')->nullable();
            $table->dateTime('close_date')->nullable();
            $table->string('observation', 255)->nullable();

            $table->foreign('mailbag_id')->references('mailbag_id')->on('mailbags')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mailbag_items');
    }
}