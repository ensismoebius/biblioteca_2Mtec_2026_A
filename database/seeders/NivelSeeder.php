<?php

namespace Database\Seeders;

use App\Models\Nivel;
use Illuminate\Database\Seeder;

/**
 * lista de 3 niveis de acesso para o banco de dados
 */

class NivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $niveis = ['Administrador', 'Bibliotecário', 'Atendente'];

        foreach ($niveis as $nivel) {
            Nivel::firstOrCreate(['NVLNOME' => $nivel]);
        }
    }
}
