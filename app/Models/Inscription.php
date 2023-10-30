<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Inscription extends Model
{
    use HasFactory, Notifiable;
    public $guarded=[''];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
