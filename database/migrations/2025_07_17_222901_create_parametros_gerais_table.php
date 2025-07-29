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
        Schema::create('parametros_gerais', function (Blueprint $table) {
            $table->integer('ger_id', true);
            $table->string('ger_nome')->nullable();
            $table->string('ger_sigla', 10)->nullable();
            $table->string('ger_cep', 20)->nullable();
            $table->integer('ger_uf')->nullable();
            $table->integer('ger_municipio')->nullable();
            $table->string('ger_bairro')->nullable();
            $table->string('ger_endereco')->nullable();
            $table->string('ger_numero', 20)->nullable();
            $table->string('ger_comp', 100)->nullable();
            $table->string('ger_telefone', 20)->nullable();
            $table->string('ger_email')->nullable();
            $table->string('ger_site')->nullable();
            $table->string('ger_logo')->nullable();
            $table->string('ger_cor_primaria', 6)->nullable();
            $table->string('ger_cor_secundaria', 6)->nullable();
            $table->boolean('ger_numeracao_anual')->nullable();
            $table->boolean('ger_guia_anual')->nullable();
            $table->boolean('ger_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_gerais');
    }
};
