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
        Schema::table('prestacao_gerenciar', function (Blueprint $table) {
            $table->foreign(['pre_cliente'], 'fk_pre_cliente')->references(['cli_id'])->on('cadastro_clientes')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestacao_gerenciar', function (Blueprint $table) {
            $table->dropForeign('fk_pre_cliente');
        });
    }
};
