<?php

namespace Database\Seeders;

use App\Models\Nivel;
use Illuminate\Database\Seeder;

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
