<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public function session(){
        return $this->belongsTo(Session::class);
    }
    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }
}
