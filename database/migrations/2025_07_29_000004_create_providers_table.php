<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('number');
            $table->string('phone');
            $table->string('phone_2')->nullable();
            $table->string('cnpj')->unique();
            $table->foreignId('municipality_id')->constrained('municipalities')->onDelete('cascade');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('zip_code');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};