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
        Schema::create('documento_gerenciar', function (Blueprint $table) {
            $table->integer('doc_id', true);
            $table->integer('doc_cliente')->nullable()->index('fk_doc_cliente');
            $table->integer('doc_orcamento')->nullable()->index('fk_doc_orcamento');
            $table->integer('doc_tipo')->nullable()->index('fk_doc_tipo');
            $table->string('doc_anexo')->nullable();
            $table->date('doc_data_emissao')->nullable();
            $table->boolean('doc_periodicidade')->nullable();
            $table->date('doc_data_vencimento')->nullable();
            $table->text('doc_observacoes')->nullable();
            $table->timestamp('doc_data_cadastro')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_gerenciar');
    }
};
