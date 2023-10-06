<?php

use App\Models\Semestre;
use App\Models\AnneeScolaire;
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
        Schema::create('annee_semestres', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AnneeScolaire::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Semestre::class)->constrained()->cascadeOnDelete();
            $table->boolean('etat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annee_semestres');
    }
};
