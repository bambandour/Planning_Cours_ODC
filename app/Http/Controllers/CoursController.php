<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoursResource;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\UserModuleResource;
use App\Models\AnneeScolaire;
use App\Models\AnneeSemestre;
use App\Models\Cours;
use App\Models\Module;
use App\Models\Semestre;
use App\Models\User;
use App\Models\UserModule;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CoursController extends Controller
{
    use ResponseTrait;

    public function index(){
        $cours=Cours::all();
        $data=CoursResource::collection($cours);
        return $this->formatResponse('La liste des cours planifiés  !',$data, true, Response::HTTP_OK);
        // return $cours;
    }

    public function store(Request $request){
            $annee=AnneeSemestre::where('etat',1)->first();
            $anneeSemestre=AnneeSemestre::where('annee_scolaire_id',$annee->id)->where('etat',1)->first();
            $cours=Cours::create([
            "annee_semestre_id"=>$anneeSemestre->id,
            "user_module_id"=>$request->user_module_id,
            "annee_classe_id"=>$request->classe,
            "heure_globale"=>$request->heure_globale
        ]);
        
        $data=CoursResource::collection($cours);
        return $this->formatResponse('Le cours a été planifié avec succés !',$data, true, Response::HTTP_CREATED,);
    }
    public function allModuleWithProf(){
        $module=Module::with('users')->get();
        return ModuleResource::collection($module);
    }
    public function getCoursByProf(Request $request,$profId){
        $prof=User::where('id',$profId)->where('role','professeur')->first();
        $userModule=UserModule::where('user_id',$prof->id)->first();
        $cours=Cours::where('user_module_id',$userModule->id)->get();
        $data=CoursResource::collection($cours);
        return $this->formatResponse('La liste des cours de '.$prof->nom.' !',$data, false, Response::HTTP_OK);
    }
}
