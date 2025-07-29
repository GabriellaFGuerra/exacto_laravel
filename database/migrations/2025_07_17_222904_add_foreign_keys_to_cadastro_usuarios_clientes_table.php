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
        Schema::table('cadastro_usuarios_clientes', function (Blueprint $table) {
            $table->foreign(['ucl_cliente'], 'fk_ucl_cliente')->references(['cli_id'])->on('cadastro_clientes')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['ucl_usuario'], 'fk_ucl_usuario')->references(['usu_id'])->on('admin_usuarios')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_usuarios_clientes', function (Blueprint $table) {
            $table->dropForeign('fk_ucl_cliente');
            $table->dropForeign('fk_ucl_usuario');
        });
    }
};
