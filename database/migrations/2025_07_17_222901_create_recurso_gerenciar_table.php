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
        Schema::create('recurso_gerenciar', function (Blueprint $table) {
            $table->integer('rec_id', true);
            $table->integer('rec_infracao')->nullable()->index('fk_rec_infracao');
            $table->string('rec_assunto')->nullable();
            $table->longText('rec_descricao')->nullable();
            $table->string('rec_recurso')->nullable();
            $table->string('rec_status', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurso_gerenciar');
    }
};
