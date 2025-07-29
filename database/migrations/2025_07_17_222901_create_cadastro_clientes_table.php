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
        Schema::create('cadastro_clientes', function (Blueprint $table) {
            $table->integer('cli_id', true);
            $table->string('cli_nome_razao')->nullable();
            $table->string('cli_cnpj', 20)->nullable();
            $table->string('cli_cep', 10)->nullable();
            $table->integer('cli_uf')->nullable();
            $table->integer('cli_municipio')->nullable();
            $table->string('cli_bairro')->nullable();
            $table->string('cli_endereco')->nullable();
            $table->string('cli_numero', 20)->nullable();
            $table->string('cli_comp', 100)->nullable();
            $table->string('cli_telefone', 30)->nullable();
            $table->string('cli_email', 200)->nullable();
            $table->string('cli_senha')->nullable();
            $table->boolean('cli_status')->nullable();
            $table->timestamp('cli_data_cadastro')->nullable()->useCurrent();
            $table->string('cli_foto')->nullable();
            $table->integer('cli_deletado')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_clientes');
    }
};
