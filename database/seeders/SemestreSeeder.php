<?php

namespace Database\Seeders;

use App\Models\Semestre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semestre::create([
            "libelle"=>"semestre_1"
        ]);
        Semestre::create([
            "libelle"=>"semestre_2"
        ]);
    }
}
