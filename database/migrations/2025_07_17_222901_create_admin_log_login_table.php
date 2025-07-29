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
        Schema::create('admin_log_login', function (Blueprint $table) {
            $table->integer('log_id', true);
            $table->integer('log_usuario')->nullable()->index('fk_log_usuario_admin');
            $table->string('log_hash')->nullable();
            $table->string('log_ip', 30)->nullable();
            $table->string('log_observacao', 200)->nullable();
            $table->string('log_cidade', 50)->nullable();
            $table->string('log_regiao', 50)->nullable();
            $table->string('log_pais', 50)->nullable();
            $table->timestamp('log_data')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_log_login');
    }
};
