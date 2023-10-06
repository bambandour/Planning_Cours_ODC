<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeSemestre extends Model
{
    use HasFactory;

    public function annee_scolaire(){
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function semestre(){
        return $this->belongsTo(Semestre::class);
    }
}
