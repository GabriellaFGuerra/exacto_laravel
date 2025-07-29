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
        Schema::table('orcamento_gerenciar', function (Blueprint $table) {
            $table->foreign(['orc_cliente'], 'fk_orc_cliente')->references(['cli_id'])->on('cadastro_clientes')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['orc_gerente_responsavel'], 'fk_orc_gerente_responsavel')->references(['usu_id'])->on('admin_usuarios')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['orc_tipo_servico'], 'fk_orc_tipo_servico')->references(['tps_id'])->on('cadastro_tipos_servicos')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['orc_usuario_responsavel'], 'fk_orc_usuario_responsavel')->references(['usu_id'])->on('admin_usuarios')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamento_gerenciar', function (Blueprint $table) {
            $table->dropForeign('fk_orc_cliente');
            $table->dropForeign('fk_orc_gerente_responsavel');
            $table->dropForeign('fk_orc_tipo_servico');
            $table->dropForeign('fk_orc_usuario_responsavel');
        });
    }
};
