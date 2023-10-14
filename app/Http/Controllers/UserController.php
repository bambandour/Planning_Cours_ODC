<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Role;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use ResponseTrait;

    public function index(){
        $users=User::all();
        return UserResource::collection($users);
    }
    public function store(Request $request){
        $user=User::create([
            "nom" => $request->nom,
            "prenom" => $request->prenom,
            "specialite" => $request->specialite,
            "date_naissance" => $request->date_naissance,
            "grade"=> $request->grade,
            "email"=> $request->email,
            "password" => bcrypt($request->password),
            "role"=>$request->role
        ]);
        return $user;
    }
    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,ods',
        ]);
        $file = $request->file('file');
        // dd($file['classe']);
        Excel::import(new UsersImport, $file);
        // return response("L'inscription a été faite avec succes !!!",200,$file);


        
    }
}
