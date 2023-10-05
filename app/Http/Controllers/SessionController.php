<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    use ResponseTrait;
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $sess=Session::create([
                "date_session" => $request->date_session,
                "h_debut" => $request->heure_debut,
                "h_fin" => $request->heure_fin,
                "nombre_heure"=>$request->nombre_heure,
                "type_session"=>$request->type_session,
                "salle_id"=>$request->salle
            ]);

            
            $sess->sessions()->attach($request->classeSessions);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // return $this->formatResponse('Une erreur est survenue lors de l\'ajout du produit.', [], false, 500,$e->getMessage());
        }

        return $sess;
    }
}
