<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salle::create([
            "libelle"=>"Albert Enisten",
            "numero"=>"101",
            "nbre_places"=>50,
        ]);
        Salle::create([
            "libelle"=>"Pythagore",
            "numero"=>"102",
            "nbre_places"=>25,
        ]);
        Salle::create([
            "libelle"=>"Justin baby",
            "numero"=>"103",
            "nbre_places"=>20,
        ]);
        Salle::create([
            "libelle"=>"Ref Dig",
            "numero"=>"104",
            "nbre_places"=>30,
        ]);
    }
}
