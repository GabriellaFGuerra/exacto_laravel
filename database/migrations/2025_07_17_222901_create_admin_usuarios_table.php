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
        Schema::create('admin_usuarios', function (Blueprint $table) {
            $table->integer('usu_id', true);
            $table->integer('usu_setor')->nullable()->index('fk_usuarios_setor');
            $table->string('usu_nome', 60)->default('null');
            $table->string('usu_email')->nullable();
            $table->string('usu_login', 60)->default('');
            $table->string('usu_senha', 60)->default('');
            $table->integer('usu_status')->nullable();
            $table->boolean('usu_notificacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_usuarios');
    }
};
