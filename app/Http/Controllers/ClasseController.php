<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnneeClasseResource;
use App\Http\Resources\ClasseResource;
use App\Models\AnneeClasse;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    use \App\Traits\ResponseTrait;
    public function index(){
        $classe=AnneeClasse::with('users')->get();
        $data= AnneeClasseResource::collection($classe);
        return $this->formatResponse('La liste des classes et ses eleves',$data,false,200);

    }
}
