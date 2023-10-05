<?php

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
        Schema::create('cours_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('heure_globale');
            $table->foreignIdFor(App\Models\Cours::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Classe::class)->constrained()->cascadeOnDelete();
            // $table->foreignIdFor(App\Models\Module::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_classes');
    }
};
