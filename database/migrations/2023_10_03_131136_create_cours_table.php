<?php

use App\Models\AnneeScolaire;
use App\Models\User;
use App\Models\Semestre;
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
            $table->foreignIdFor(AnneeScolaire::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Semestre::class)->constrained();
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
