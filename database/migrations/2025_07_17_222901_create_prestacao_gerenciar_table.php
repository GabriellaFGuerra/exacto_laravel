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
        Schema::create('prestacao_gerenciar', function (Blueprint $table) {
            $table->integer('pre_id', true);
            $table->integer('pre_cliente')->nullable()->index('fk_pre_cliente');
            $table->string('pre_referencia', 8)->nullable();
            $table->date('pre_data_envio')->nullable();
            $table->string('pre_enviado_por')->nullable();
            $table->text('pre_observacoes')->nullable();
            $table->timestamp('pre_data_cadastro')->nullable()->useCurrent();
            $table->string('pre_comprovante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestacao_gerenciar');
    }
};
