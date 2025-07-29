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
        Schema::create('cadastro_status_orcamento', function (Blueprint $table) {
            $table->integer('sto_id', true);
            $table->integer('sto_orcamento')->nullable()->default(0)->index('sto_orcamento');
            $table->boolean('sto_status')->unsigned()->nullable();
            $table->integer('sto_fornecedor_aprovado')->nullable();
            $table->text('sto_observacao')->nullable();
            $table->timestamp('sto_data')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_status_orcamento');
    }
};
