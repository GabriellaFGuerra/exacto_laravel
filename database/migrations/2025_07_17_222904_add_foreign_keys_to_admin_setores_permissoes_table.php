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
        Schema::table('admin_setores_permissoes', function (Blueprint $table) {
            $table->foreign(['sep_modulo'], 'fk_sep_modulo')->references(['mod_id'])->on('admin_modulos')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['sep_setor'], 'fk_sep_setor')->references(['set_id'])->on('admin_setores')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['sep_submodulo'], 'fk_sep_submodulo')->references(['sub_id'])->on('admin_submodulos')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_setores_permissoes', function (Blueprint $table) {
            $table->dropForeign('fk_sep_modulo');
            $table->dropForeign('fk_sep_setor');
            $table->dropForeign('fk_sep_submodulo');
        });
    }
};
