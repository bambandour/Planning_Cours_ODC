<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [''];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function role(){
        return $this->belongsTo(User::class);        
    }

    public function user_modules(){
        return $this->belongsToMany(Module::class,'user_modules','module_id','user_id')
            ->withPivot('id');
    }
    public function users(){
        return $this->belongsToMany(AnneeClasse::class,'inscriptions')
            ->withPivot('id');
    }
    public function classes()
    {
        return $this->belongsToMany(AnneeClasse::class,'inscriptions');
    }

    public function anneeClasses()
    {
        return $this->belongsToMany(AnneeClasse::class);
    }

    public function anneeSemestres()
    {
        return $this->belongsToMany(AnneeSemestre::class);
    }
}
