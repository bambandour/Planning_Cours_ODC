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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom',50);
            $table->string('prenom',100);
            $table->string('specialite',100)->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('grade',['docteur','professeur','maitre'])->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
