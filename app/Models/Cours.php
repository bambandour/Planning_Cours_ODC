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
            ->withPivot('heure_globale','id');
    }
    public function annee_semestre(){
        return $this->belongsTo(AnneeSemestre::class);
    }
    public function annee_classe(){
        return $this->belongsTo(AnneeClasse::class);
    }
    public function user_module(){
        return $this->belongsTo(UserModule::class);
    }
}
