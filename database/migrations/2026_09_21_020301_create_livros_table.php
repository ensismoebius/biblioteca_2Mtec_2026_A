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
        Schema::create('LIVROS', function (Blueprint $table) {
            // Chave Primária Auto-Incremento
            $table->id('LVRCODIGO');

            //título: VARCHAR(150), NOT NULL e com Índice para busca rápida
            $table->string('LVRTITULO', 150)->index();

            //isbn: VARCHAR(15), Nulo permitido, Único quando preenchido
            $table->string('LVRISBN', 15)->nullable()->unique();

            //edição: Inteiro, Nulo permitido
            $table->integer('LVREDICAO')->nullable();

            //data de publicação: DATE, Nulo permitido
            $table->date('LVRDTPUBLIC')->nullable();

            //sinopse: TEXT, Nulo permitido
            $table->text('LVRSINOPSE')->nullable();

            //idade Mínima: Inteiro, Nulo permitido
            $table->integer('LVRFAIXAETARIA')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LIVROS');
    }
};