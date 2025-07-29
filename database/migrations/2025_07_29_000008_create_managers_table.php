<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id('manager_id');
            $table->string('name', 255);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('managers');
    }
}