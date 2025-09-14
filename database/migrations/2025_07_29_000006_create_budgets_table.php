<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->string('custom_service_type', 255)->nullable();
            $table->json('spreadsheets')->nullable();
            $table->integer('progress')->nullable()->default(0);
            $table->text('observation')->nullable();
            $table->date('approval_date')->nullable();
            $table->unsignedBigInteger('responsible_user_id')->nullable();
            $table->unsignedBigInteger('responsible_manager_id')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['open', 'approved', 'rejected', 'pending'])->default('open');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('set null');
            $table->foreign('responsible_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsible_manager_id')->references('id')->on('managers')->onDelete('set null');

        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}