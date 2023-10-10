<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $guarded=['id'];
    public function cours(){
        return $this->belongsTo(Cours::class);
    }

    public function salle(){
        return $this->belongsTo(Salle::class);
    }
}
