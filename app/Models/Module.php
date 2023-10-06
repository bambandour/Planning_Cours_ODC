<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    public $guarded=[''];

    public function users(){
        return $this->belongsToMany(User::class,'user_modules','module_id','user_id')
            ->withPivot('id');
    }
}
