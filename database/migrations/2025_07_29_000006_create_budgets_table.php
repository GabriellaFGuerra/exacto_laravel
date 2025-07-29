<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id('budget_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->string('custom_service_type', 255)->nullable();
            $table->string('spreadsheet', 255)->nullable();
            $table->text('progress')->nullable();
            $table->text('observation')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->date('approval_date')->nullable();
            $table->unsignedBigInteger('responsible_user_id')->nullable();
            $table->unsignedBigInteger('responsible_manager_id')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['open', 'approved', 'rejected', 'pending'])->default('open');

            $table->foreign('client_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('service_type_id')->references('service_type_id')->on('service_types')->onDelete('set null');
            $table->foreign('responsible_user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('responsible_manager_id')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}