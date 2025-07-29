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
        Schema::create('end_enderecos', function (Blueprint $table) {
            $table->integer('end_id', true);
            $table->string('end_endereco', 300)->nullable();
            $table->integer('end_bairro')->nullable()->index('fk_end_bairro');
            $table->integer('end_municipio')->nullable()->index('fk_end_municipio');
            $table->integer('end_uf')->nullable()->index('fk_end_uf');
            $table->string('end_cep', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_enderecos');
    }
};
