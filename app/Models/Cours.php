<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;
    public $guarded=[''];

    public function cours(){
        return $this->belongsToMany(Classe::class,'cours_classes')
            ->withPivot('heure_globale');
    }
}
