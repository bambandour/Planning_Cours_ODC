<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Cours;
use App\Models\Semestre;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursController extends Controller
{
    use ResponseTrait;
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $semestre=Semestre::where('etat',1)->first();
            $annee=AnneeScolaire::where('etat',1)->first();
        $cours=Cours::create([
            "annee_scolaire_id"=>$annee->id,
            "semestre_id"=>$semestre->id,
            "user_module_id"=>$request->user_module_id,
        ]);
        $cours->cours()->attach($request->classes);
        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // return $this->formatResponse('Une erreur est survenue lors de l\'ajout du produit.', [], false, 500,$e->getMessage());
        }

        return $cours;
    }
}
