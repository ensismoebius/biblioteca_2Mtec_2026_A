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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('CLICODIGO');                        
            $table->string('CLINOME', 150);                 
            $table->index('CLINOME');                        
            $table->string('CLICPF', 15)->unique();          
            $table->string('CLITELEFONE', 20)->nullable();   
            $table->string('CLIEMAIL', 150)->nullable();     
            $table->date('CLIDTNASC');                       
            $table->date('CLIDTCAD');           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
