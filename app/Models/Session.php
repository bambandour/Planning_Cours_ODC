<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $guarded=['id'];
    public function cours(){
        return $this->belongsTo(Cours::class);
    }

    public function salle(){
        return $this->belongsTo(Salle::class);
    }
    public function demande()
    {
        return $this->hasOne(Demande::class);
    }

}
