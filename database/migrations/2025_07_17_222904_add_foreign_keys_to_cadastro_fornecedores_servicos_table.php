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
        Schema::table('cadastro_fornecedores_servicos', function (Blueprint $table) {
            $table->foreign(['fse_fornecedor'], 'fk_fse_fornecedor')->references(['for_id'])->on('cadastro_fornecedores')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['fse_servico'], 'fk_fse_servico')->references(['tps_id'])->on('cadastro_tipos_servicos')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_fornecedores_servicos', function (Blueprint $table) {
            $table->dropForeign('fk_fse_fornecedor');
            $table->dropForeign('fk_fse_servico');
        });
    }
};
