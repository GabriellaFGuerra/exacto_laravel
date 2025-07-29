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
        Schema::table('documento_gerenciar', function (Blueprint $table) {
            $table->foreign(['doc_cliente'], 'fk_doc_cliente')->references(['cli_id'])->on('cadastro_clientes')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['doc_orcamento'], 'fk_doc_orcamento')->references(['orc_id'])->on('orcamento_gerenciar')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['doc_tipo'], 'fk_doc_tipo')->references(['tpd_id'])->on('cadastro_tipos_docs')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documento_gerenciar', function (Blueprint $table) {
            $table->dropForeign('fk_doc_cliente');
            $table->dropForeign('fk_doc_orcamento');
            $table->dropForeign('fk_doc_tipo');
        });
    }
};
