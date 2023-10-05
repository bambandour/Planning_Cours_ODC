<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseTrait;
    public function store(Request $request){
        $user=User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "specialite" => $request->specialite,
            "date_naissance" => $request->date_naissance,
            "grade"=> $request->grade,
            "email"=> $request->email,
            "password" => bcrypt($request->password),
            "role_id"=>$request->role
        ]);
        return $user;
    }
}