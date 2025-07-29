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
        Schema::create('end_municipios', function (Blueprint $table) {
            $table->integer('mun_id', true);
            $table->string('mun_nome', 200)->nullable();
            $table->integer('mun_uf')->nullable();
            $table->string('mun_cep2', 15)->nullable();
            $table->string('mun_cep', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_municipios');
    }
};
