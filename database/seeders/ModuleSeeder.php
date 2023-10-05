<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            "libelle"=>"PHP"
        ]);
        Module::create([
            "libelle"=>"JAVA"
        ]);
        Module::create([
            "libelle"=>"HTML"
        ]);
        Module::create([
            "libelle"=>"Laravel"
        ]);
    }
}
