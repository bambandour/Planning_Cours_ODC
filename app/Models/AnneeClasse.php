<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeClasse extends Model
{
    use HasFactory;
    public $guarded=[''];

    public function annee_scolaire(){
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function classe(){
        return $this->belongsTo(Classe::class);
    }
    public function classes(){
        return $this->belongsToMany(User::class,'inscriptions')
            ->withPivot('id');
    }
}
