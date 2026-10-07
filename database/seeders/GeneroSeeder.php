<?php

namespace Database\Seeders;

use App\Models\Genero;
use Illuminate\Database\Seeder;

/**
 * lista de 12 generos para o banco de dados
 */

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $Generos = ['Romance', 'Ficção Científica', 'Fantasia', 'Terror', 'Biografia', 'História',
            'Didático', 'Infantil', 'Poesia', 'Técnico', 'Autoajuda', 'Mangá'];

        foreach ($Generos as $genero) {
            Genero::firstOrCreate(['GNRNOME' => $genero]);
        }
    }
}
