<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('EMPRESTIMOS', function (Blueprint $table) {
            //PK auto-incremento(EMPCODIGO)
            $table->id('EMPCODIGO');

            $table->foreignId('EMPLIVRO')
                  ->constrained('EXEMPLARES', 'EXMCODIGO')
                  ->onDelete('restrict');

	        $table->foreignId('EMPUSUARIO')
                  ->constrained('USUARIOS', 'USRCODIGO')
                  ->onDelete('restrict');

            $table->foreignId('EMPCLIENTE')
                  ->constrained('CLIENTES', 'CLICODIGO')
                  ->onDelete('restrict');


            $table->date('EMPDTEMPR');

            $table->date('EMPDTDEVOL')->nullable();

            $table->timestamps();

            //índice composto para otimizar a consulta de empréstimos ativos
            $table->index(['EMPCLIENTE', 'EMPDTDEVOL']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('EMPRESTIMOS');
    }
};