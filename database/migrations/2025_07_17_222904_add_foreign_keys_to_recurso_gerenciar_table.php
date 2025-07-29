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
        Schema::table('recurso_gerenciar', function (Blueprint $table) {
            $table->foreign(['rec_infracao'], 'fk_rec_infracao')->references(['inf_id'])->on('infracoes_gerenciar')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recurso_gerenciar', function (Blueprint $table) {
            $table->dropForeign('fk_rec_infracao');
        });
    }
};
