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
        Schema::create('cadastro_fornecedores', function (Blueprint $table) {
            $table->integer('for_id', true);
            $table->string('for_nome_razao')->nullable();
            $table->string('for_cnpj', 20)->nullable();
            $table->string('for_cep', 10)->nullable();
            $table->integer('for_uf')->nullable();
            $table->integer('for_municipio')->nullable();
            $table->string('for_bairro')->nullable();
            $table->string('for_endereco')->nullable();
            $table->string('for_numero', 20)->nullable();
            $table->string('for_comp', 100)->nullable();
            $table->string('for_telefone', 30)->nullable();
            $table->string('for_telefone2', 30)->nullable();
            $table->string('for_telefone3', 30)->nullable();
            $table->string('for_email', 200)->nullable();
            $table->boolean('for_autonomo')->nullable();
            $table->string('for_nome_mae')->nullable();
            $table->date('for_data_nasc')->nullable();
            $table->string('for_rg', 30)->nullable();
            $table->string('for_cpf', 20)->nullable();
            $table->string('for_pis', 50)->nullable();
            $table->string('for_banco', 100)->nullable();
            $table->string('for_agencia', 50)->nullable();
            $table->string('for_cc', 50)->nullable();
            $table->boolean('for_status')->nullable();
            $table->text('for_observacoes')->nullable();
            $table->timestamp('for_data_cadastro')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_fornecedores');
    }
};
