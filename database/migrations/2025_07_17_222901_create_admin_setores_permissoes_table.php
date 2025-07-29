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
        Schema::create('admin_setores_permissoes', function (Blueprint $table) {
            $table->integer('sep_id', true);
            $table->integer('sep_setor')->nullable()->index('fk_sep_setor');
            $table->integer('sep_modulo')->nullable()->index('fk_sep_modulo');
            $table->integer('sep_submodulo')->nullable()->index('fk_sep_submodulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_setores_permissoes');
    }
};
