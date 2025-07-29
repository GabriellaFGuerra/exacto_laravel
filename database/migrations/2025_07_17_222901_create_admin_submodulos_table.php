<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_submodulos', function (Blueprint $table) {
            $table->integer('sub_id', true);
            $table->integer('sub_modulo')->nullable()->index('fk_sub_modulo');
            $table->string('sub_nome', 100)->nullable();
            $table->string('sub_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_submodulos');
    }
};
