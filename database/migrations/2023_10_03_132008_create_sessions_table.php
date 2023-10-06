<?php

use App\Models\Salle;
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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->date('date_session');
            $table->integer('h_debut');
            $table->integer('h_fin');
            $table->integer('nombre_heure');
            $table->enum('type',['presentiel','online'])->default('presentiel');
            $table->enum('validated',['accept','refus','pending'])->nullable();
            $table->boolean('etat')->default(false);
            $table->foreignIdFor(Salle::class)->constrained()->nullable()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
