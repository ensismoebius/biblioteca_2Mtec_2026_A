<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            \App\Models\Nivel::firstOrCreate(['NVLNOME' => $nivel]);
        }
    }
}
