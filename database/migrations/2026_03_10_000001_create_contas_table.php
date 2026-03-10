<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->string('tipo', 30)->default('conta');
            $table->decimal('saldo_inicial', 14, 2)->default(0);
            $table->decimal('saldo_atual', 14, 2)->default(0);
            $table->string('cor', 20)->nullable();
            $table->string('icone', 50)->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
