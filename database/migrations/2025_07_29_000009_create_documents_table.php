<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('budget_id')->nullable();
            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->string('attachment', 255)->nullable();
            $table->date('issue_date')->nullable();
            $table->tinyInteger('periodicity')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('observation')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('client_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('budget_id')->references('budget_id')->on('budgets')->onDelete('set null');
            $table->foreign('document_type_id')->references('document_type_id')->on('document_types')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}