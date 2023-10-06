<?php

use App\Models\AnneeClasse;
use App\Models\AnneeScolaire;
use App\Models\AnneeSemestre;
use App\Models\Semestre;
use App\Models\UserModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->integer('heure_globale');
            $table->foreignIdFor(AnneeSemestre::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(AnneeClasse::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
