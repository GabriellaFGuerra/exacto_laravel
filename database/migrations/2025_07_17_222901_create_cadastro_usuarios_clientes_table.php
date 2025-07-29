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
        Schema::create('cadastro_usuarios_clientes', function (Blueprint $table) {
            $table->integer('ucl_id', true);
            $table->integer('ucl_usuario')->nullable()->index('fk_ucl_usuario');
            $table->integer('ucl_cliente')->nullable()->index('fk_ucl_cliente');
            $table->timestamp('ucl_data')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_usuarios_clientes');
    }
};
