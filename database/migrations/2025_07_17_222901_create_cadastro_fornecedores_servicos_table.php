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
        Schema::create('cadastro_fornecedores_servicos', function (Blueprint $table) {
            $table->integer('fse_id', true);
            $table->integer('fse_fornecedor')->nullable()->index('fk_fse_fornecedor');
            $table->integer('fse_servico')->nullable()->index('fk_fse_servico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_fornecedores_servicos');
    }
};
