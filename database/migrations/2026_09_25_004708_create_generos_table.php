<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    { //criando a tabela pedida na issue 10
        Schema::create('GENEROS', function (Blueprint $table) {
            //cria a chave primária auto-incrementável
            //para numerar e identificar cada gênero unicamente.
            //como fantasia romance etc
            $table->id('GNRCODIGO');
            // Nome do gênero (obrigatório e único, máx. 30 caracteres)
            //único pra ninguem criar dois gêneros com mesmo nome
            $table->string('GNRNOME', 30)->unique();
            //cria duas colunas pra controlar o registro
            //do que foi criado e atualizado(datas)
            $table->timestamps();
        });
    }

    // Desfaz a migração (apaga a tabela se precisar de rollback)
    public function down(): void
    {
        Schema::dropIfExists('GENEROS');
    } //é isso gente amaram?
};
