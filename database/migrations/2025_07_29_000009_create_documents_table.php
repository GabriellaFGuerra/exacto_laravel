<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('budget_id')->nullable();
            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->string('attachment', 255);
            $table->string('title', 255);
            $table->date('issue_date')->nullable();
            $table->tinyInteger('periodicity')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('set null');
            $table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('set null');

        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}