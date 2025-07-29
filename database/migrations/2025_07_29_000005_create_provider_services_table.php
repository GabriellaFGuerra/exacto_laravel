<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderServicesTable extends Migration
{
    public function up()
    {
        Schema::create('provider_services', function (Blueprint $table) {
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('service_type_id');
            $table->primary(['provider_id', 'service_type_id']);

            $table->foreign('provider_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('service_type_id')->references('service_type_id')->on('service_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('provider_services');
    }
}