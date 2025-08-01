<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitiesSeeder extends Seeder
{
    public function run()
    {
        $json = json_decode(file_get_contents(database_path('data/estados_cidades.json')), true);

        $ufs = DB::table('federative_units')->pluck('uf_id', 'abbreviation')->toArray();

        $cidades = [];
        foreach ($json['estados'] as $estado) {
            $uf_id = $ufs[$estado['sigla']];
            foreach ($estado['cidades'] as $cidade) {
                $cidades[] = [
                    'name' => $cidade,
                    'uf_id' => $uf_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        // Insere em lotes
        foreach (array_chunk($cidades, 1000) as $chunk) {
            DB::table('municipalities')->insert($chunk);
        }
    }
}