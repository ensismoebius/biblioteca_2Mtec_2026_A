<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('CLASSIFICACAO', function (Blueprint $table) {
            // FK -> LIVROS.LVRCODIGO (parte da PK composta)
            $table->foreignId('CLSLIVRO')
                ->constrained('LIVROS', 'LVRCODIGO')
                ->onDelete('cascade');

            // FK -> GENEROS.GNRCODIGO (parte da PK composta)
            $table->foreignId('CLSGENERO')
                ->constrained('GENEROS', 'GNRCODIGO')
                ->onDelete('cascade');

            $table->boolean('CLSPRINCIPAL')->nullable();

            // PK composta: impede o mesmo livro duas vezes no mesmo gênero
            $table->primary(['CLSLIVRO', 'CLSGENERO']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CLASSIFICACAO');
    }
};
