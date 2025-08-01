<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FederativeUnitsSeeder extends Seeder
{
    public function run()
    {
        $json = json_decode(file_get_contents(database_path('data/estados_cidades.json')), true);

        $estados = [];
        foreach ($json['estados'] as $estado) {
            $estados[] = [
                'name' => $estado['nome'],
                'abbreviation' => $estado['sigla'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('federative_units')->insert($estados);
    }
}