<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classe::create([
            "libelle"=>"Dev Web/Mobile",
            "filiere"=>"Informatique",
            "effectif"=>50,
            "level_id"=>1
        ]);
        Classe::create([
            "libelle"=>"Dev Data",
            "filiere"=>"Science de données",
            "effectif"=>25,
            "level_id"=>1
        ]);
        Classe::create([
            "libelle"=>"Ref Dig 2",
            "filiere"=>"Communication Digitale",
            "effectif"=>20,
            "level_id"=>1
        ]);
        Classe::create([
            "libelle"=>"Ref Dig 1",
            "filiere"=>"Communication Digitale",
            "effectif"=>30,
            "level_id"=>1
        ]);
        
    }
}
