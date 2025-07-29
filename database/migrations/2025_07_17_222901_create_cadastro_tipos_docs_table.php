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
        Schema::create('cadastro_tipos_docs', function (Blueprint $table) {
            $table->integer('tpd_id', true);
            $table->string('tpd_nome')->nullable();
            $table->boolean('tpd_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_tipos_docs');
    }
};
