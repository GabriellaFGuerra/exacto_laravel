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
        Schema::table('cliente_log_login', function (Blueprint $table) {
            $table->foreign(['log_usuario'], 'fk_cliente_log_usuario')->references(['cli_id'])->on('cadastro_clientes')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cliente_log_login', function (Blueprint $table) {
            $table->dropForeign('fk_cliente_log_usuario');
        });
    }
};
