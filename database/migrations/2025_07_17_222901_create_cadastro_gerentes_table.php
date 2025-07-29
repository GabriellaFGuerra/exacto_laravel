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
        Schema::create('cadastro_gerentes', function (Blueprint $table) {
            $table->integer('ger_id', true);
            $table->string('ger_nome')->nullable();
            $table->timestamp('ger_data_cadastro')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_gerentes');
    }
};
