<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetProvidersTable extends Migration
{
    public function up()
    {
        Schema::create('budget_providers', function (Blueprint $table) {
            $table->id('budget_provider_id');
            $table->unsignedBigInteger('budget_id');
            $table->unsignedBigInteger('provider_id');
            $table->decimal('value', 10, 2)->nullable();
            $table->string('observation', 255)->nullable();
            $table->string('attachment', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('budget_id')->references('budget_id')->on('budgets')->onDelete('cascade');
            $table->foreign('provider_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_providers');
    }
}