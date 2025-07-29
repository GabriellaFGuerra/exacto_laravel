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
        Schema::create('infracoes_gerenciar', function (Blueprint $table) {
            $table->integer('inf_id', true);
            $table->integer('inf_cliente')->nullable()->index('fk_inf_cliente');
            $table->string('inf_tipo')->nullable();
            $table->string('inf_ano', 4)->nullable();
            $table->string('inf_cidade')->nullable();
            $table->date('inf_data')->nullable();
            $table->string('inf_proprietario')->nullable();
            $table->string('inf_apto')->nullable();
            $table->string('inf_bloco', 20)->nullable();
            $table->string('inf_endereco')->nullable();
            $table->string('inf_email')->nullable();
            $table->text('inf_desc_irregularidade')->nullable();
            $table->string('inf_assunto')->nullable();
            $table->longText('inf_desc_artigo')->nullable();
            $table->longText('inf_desc_notificacao')->nullable();
            $table->string('inf_comprovante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infracoes_gerenciar');
    }
};
