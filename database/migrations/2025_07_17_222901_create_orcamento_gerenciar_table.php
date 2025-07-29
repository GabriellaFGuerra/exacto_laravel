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
        Schema::create('orcamento_gerenciar', function (Blueprint $table) {
            $table->integer('orc_id', true);
            $table->integer('orc_cliente')->nullable()->index('fk_orc_cliente');
            $table->integer('orc_tipo_servico')->nullable()->index('fk_orc_tipo_servico');
            $table->string('orc_tipo_servico_cliente')->nullable();
            $table->string('orc_planilha')->nullable();
            $table->text('orc_andamento')->nullable();
            $table->text('orc_observacoes')->nullable();
            $table->timestamp('orc_data_cadastro')->nullable()->useCurrent();
            $table->date('orc_data_aprovacao')->nullable();
            $table->integer('orc_usuario_responsavel')->nullable()->index('fk_orc_usuario_responsavel');
            $table->integer('orc_gerente_responsavel')->nullable()->index('fk_orc_gerente_responsavel');
            $table->date('orc_prazo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_gerenciar');
    }
};
