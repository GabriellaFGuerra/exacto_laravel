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
        Schema::create('end_uf', function (Blueprint $table) {
            $table->integer('uf_id', true);
            $table->string('uf_nome', 150)->nullable();
            $table->string('uf_sigla', 2)->nullable();
            $table->integer('uf_ibge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_uf');
    }
};
