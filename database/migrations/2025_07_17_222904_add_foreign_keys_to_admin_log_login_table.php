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
        Schema::table('admin_log_login', function (Blueprint $table) {
            $table->foreign(['log_usuario'], 'fk_log_usuario_admin')->references(['usu_id'])->on('admin_usuarios')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_log_login', function (Blueprint $table) {
            $table->dropForeign('fk_log_usuario_admin');
        });
    }
};
