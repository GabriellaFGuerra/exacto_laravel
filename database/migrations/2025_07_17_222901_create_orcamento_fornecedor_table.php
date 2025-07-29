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
        Schema::create('orcamento_fornecedor', function (Blueprint $table) {
            $table->integer('orf_id', true);
            $table->unsignedInteger('orf_orcamento')->nullable()->default(0)->index('orf_orcamento');
            $table->integer('orf_fornecedor')->nullable()->index('orf_fornecedor');
            $table->decimal('orf_valor', 10)->nullable();
            $table->string('orf_obs')->nullable();
            $table->string('orf_anexo')->nullable();
            $table->timestamp('orf_data_cadastro')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_fornecedor');
    }
};
