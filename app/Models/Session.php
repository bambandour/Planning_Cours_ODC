<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $guarded=['id'];
    public function sessions(){
        return $this->belongsToMany(CoursClasse::class,'classe_sessions');
    }
}
