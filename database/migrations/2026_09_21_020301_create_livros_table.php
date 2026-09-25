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
            $table->id('LVRCODIGO');
            $table->string('LVRTITULO', 150)->index();
            $table->string('LVRISBN', 15)->nullable()->unique();
            $table->integer('LVREDICAO')->nullable();
            $table->date('LVRDTPUBLIC')->nullable();
            $table->text('LVRSINOPSE')->nullable();
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
