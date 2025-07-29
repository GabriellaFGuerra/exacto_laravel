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
        Schema::create('admin_modulos', function (Blueprint $table) {
            $table->integer('mod_id', true);
            $table->string('mod_nome', 100)->nullable();
            $table->string('mod_link')->nullable()->default('#');
            $table->integer('mod_ordem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_modulos');
    }
};
