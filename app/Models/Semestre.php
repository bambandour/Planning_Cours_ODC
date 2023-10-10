<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;

    public function semestres(){
        return $this->belongsToMany(AnneeScolaire::class,'annee_semestres')
            ->withPivot('id');
    }
}
