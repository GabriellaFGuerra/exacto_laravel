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
        Schema::table('end_enderecos', function (Blueprint $table) {
            $table->foreign(['end_bairro'], 'fk_end_bairro')->references(['bai_id'])->on('end_bairros')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['end_municipio'], 'fk_end_municipio')->references(['mun_id'])->on('end_municipios')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['end_uf'], 'fk_end_uf')->references(['uf_id'])->on('end_uf')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('end_enderecos', function (Blueprint $table) {
            $table->dropForeign('fk_end_bairro');
            $table->dropForeign('fk_end_municipio');
            $table->dropForeign('fk_end_uf');
        });
    }
};
