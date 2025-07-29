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
        Schema::table('cadastro_status_orcamento', function (Blueprint $table) {
            $table->foreign(['sto_orcamento'], 'cadastro_status_orcamento_ibfk_1')->references(['orc_id'])->on('orcamento_gerenciar')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_status_orcamento', function (Blueprint $table) {
            $table->dropForeign('cadastro_status_orcamento_ibfk_1');
        });
    }
};
