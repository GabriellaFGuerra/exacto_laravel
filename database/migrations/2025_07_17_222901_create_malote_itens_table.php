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
        Schema::create('malote_itens', function (Blueprint $table) {
            $table->integer('mai_id', true);
            $table->integer('mai_malote')->nullable();
            $table->string('mai_fornecedor', 200)->nullable();
            $table->string('mai_tipo_documento', 200)->nullable();
            $table->string('mai_num_cheque', 50)->nullable();
            $table->decimal('mai_valor', 10)->nullable();
            $table->date('mai_data_vencimento')->nullable();
            $table->boolean('mai_baixado')->nullable();
            $table->dateTime('mai_data_baixa')->nullable();
            $table->string('mai_observacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('malote_itens');
    }
};
