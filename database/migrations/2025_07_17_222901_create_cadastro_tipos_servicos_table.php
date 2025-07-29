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
        Schema::create('cadastro_tipos_servicos', function (Blueprint $table) {
            $table->integer('tps_id', true);
            $table->string('tps_nome')->nullable();
            $table->boolean('tps_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_tipos_servicos');
    }
};
