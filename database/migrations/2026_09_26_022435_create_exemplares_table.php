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
        Schema::create('EXEMPLARES', function (Blueprint $table) {
            $table->id('EXMCODIGO');
            $table->foreignId('EXMLIVRO')
                ->constrained('LIVROS', 'LVRCODIGO')
                ->onDelete('restrict');
            $table->date('EXMDTAQUISICAO')->nullable();
            $table->boolean('EXMDISPONIVEL')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('EXEMPLARES');
    }
};
