<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $Generos = ["Romance", "Ficção Científica", "Fantasia", "Terror", "Biografia", "História",
        "Didático", "Infantil", "Poesia", "Técnico", "Autoajuda", "Mangá"];

        foreach ($Generos as $genero) {
            \App\Models\Genero::firstOrCreate(['GNRNOME' => $genero]);
        }
    }
}
