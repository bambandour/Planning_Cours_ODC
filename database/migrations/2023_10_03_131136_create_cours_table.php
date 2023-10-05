<?php

use App\Models\AnneeScolaire;
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
            $table->foreignIdFor(AnneeScolaire::class)->constrained()->cascadeOnDelete();
            // $table->foreignId('users_modules_id')->constrained('user_modules')->cascadeOnDelete();
            $table->foreignIdFor(Semestre::class)->constrained()->cascadeOnDelete();
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
