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
        //cria a tabela autoria
         Schema::create('AUTORIA', function (Blueprint $table) {
            //cria campos, guardando o código do livro e autor
            $table->unsignedBigInteger('ATRLIVRO');
            $table->unsignedBigInteger('ATRAUTOR');
            //cria um campo para saber se o autor é ou não o autor principal do livro
            $table->boolean('ATRPRINCIPAL');

            //chave primária composta, onde a mesma combinação de livro e autor não pode aparecer duas vezes
            $table->primary(['ATRLIVRO', 'ATRAUTOR']);

            $table->foreign('ATRLIVRO')
                ->references('LVRCODIGO')
                ->on('LIVROS')
                ->onDelete('cascade');
                //caso um registro seja apagado da tabela livros,
                //o onDelete apaga todas as suas relações na tabela autoria
             
            $table->foreign('ATRAUTOR')
                ->references('AUTCODIGO')
                ->on('AUTORES')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //desfaz a migração
        Schema::dropIfExists('AUTORIA');
    }
};
