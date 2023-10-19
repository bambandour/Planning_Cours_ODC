<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classe extends Model
{
    use HasFactory;
    public $guarded=[''];
    public function level(){
        return $this->belongsTo(Level::class);
    }

    public function classes(){
        return $this->belongsToMany(AnneeScolaire::class,'annee_classes')
            ->withPivot('id','effectif');
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'inscriptions', 'classe_id', 'user_id');
    }


}
