<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration da tabela niveis (níveis de acesso dos funcionários).
 */
return new class extends Migration
{
    /**
     * Cria a tabela niveis conforme o diagrama ER.
     */
    public function up(): void
    {
        Schema::create('NIVEIS', function (Blueprint $table) {
            $table->id('NVLCODIGO');
            $table->string('NVLNOME', 30)->unique();
        });
    }

    /**
     * Remove a tabela niveis.
     */
    public function down(): void
    {
        Schema::dropIfExists('NIVEIS');
    }
};
