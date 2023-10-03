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
        Schema::create('classe_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Session::class)->constrained();
            $table->foreignIdFor(App\Models\CoursClasse::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_sessions');
    }
};
