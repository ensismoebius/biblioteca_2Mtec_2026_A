<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('USUARIOS', function(Blueprint $table) {
            //PK auto-incremento
            $table->id('USRCODIGO');

            //USRNOME: VARCHAR(150), NOT NULL
            $table->string('USRNOME', 150);

            //USRCPF: VARCHAR(15), único
            $table->string('USRCPF', 15)->unique();

            //USRLOGIN: VARCHAR(20), único(usado para autenticação)
            $table->string('USRLOGIN', 20)->unique();

            //USRSENHA: C(255) ajustado para hashes do laravel
            $table->string('USRSENHA', 255);

            //FK USRNIVEL -> NIVEIS(NVLCODIGO)
	          $table->foreignId('USRNIVEL')
                  ->constrained('NIVEIS', 'NVLCODIGO')
                  ->onDelete('restrict');

            //USREMAIL: VARCHAR(150), único
            $table->string('USREMAIL', 150)->unique();

            //USRDTCAD: DATE, NOT NULL(data de cadastro)
            $table->date('USRDTCAD');

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('USUARIOS');
    }
};