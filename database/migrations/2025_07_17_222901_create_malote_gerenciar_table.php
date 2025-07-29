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
        Schema::create('malote_gerenciar', function (Blueprint $table) {
            $table->integer('mal_id', true);
            $table->integer('mal_cliente')->nullable()->index('fk_mal_cliente');
            $table->string('mal_lacre')->nullable();
            $table->text('mal_observacoes')->nullable();
            $table->timestamp('mal_data_cadastro')->nullable()->useCurrent();
            $table->string('mal_pg_eletronico')->nullable();
            $table->string('mal_pg_eletronico2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('malote_gerenciar');
    }
};
