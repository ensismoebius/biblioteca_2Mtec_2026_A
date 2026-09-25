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
        Schema::create('AUTORES', function (Blueprint $table) {
            // Chave primária da tabela, auto-incremento
            $table->id('AUTCODIGO');
            // Nome completo do autor, obrigatório (NOT NULL)
            $table->string('AUTNOME', 150);
            // Pseudónimo opcional do autor
            $table->string('AUTPSEUDONIMO', 150)->nullable();
            // Biografia do autor, opcional
            $table->text('AUTBIOGRAFIA')->nullable();
            // País de nascimento opcional
            $table->string('AUTPAISNASC', 100)->nullable();
            // Colunas de timestamps para created_at e updated_at
            $table->timestamps();
        });
    }

    //Reverte a nossa queridissima migration, removendo a tabela AUTORES
    public function down(): void
    {
        Schema::dropIfExists('AUTORES');
    }
};
