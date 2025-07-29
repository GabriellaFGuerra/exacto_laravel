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
        Schema::create('end_bairros', function (Blueprint $table) {
            $table->integer('bai_id', true);
            $table->string('bai_nome', 200)->nullable();
            $table->string('bai_municipio', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_bairros');
    }
};
