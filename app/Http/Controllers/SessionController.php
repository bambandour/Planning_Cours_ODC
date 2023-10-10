<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Http\Resources\SalleResource;
use App\Http\Resources\SessionResource;
use App\Models\AnneeClasse;
use App\Models\AnneeScolaire;
use App\Models\AnneeSemestre;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Module;
use App\Models\Salle;
use App\Models\Semestre;
use App\Models\Session;
use App\Models\User;
use App\Models\UserModule;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    use ResponseTrait;
    public function index(){
        $sal=Salle::all();
        $ses=Session::all();
        $session= SessionResource::collection($ses);
        $salle=SalleResource::collection($sal);
        $data=[
            "session"=>$session,
            "salle"=>$salle
        ];

        return $this->formatResponse('La liste des sessions de cours planifiés  !',$data, true, Response::HTTP_OK);

    }
    public function store(Request $request){

        $cours=Cours::where('id',$request->cours)->first();
        $classeAnnee=Cours::where('annee_classe_id',$cours->annee_classe_id)->first();
        $classe=AnneeClasse::where('classe_id',$classeAnnee->id)->first();

        if ($request->heure_debut >= $request->heure_fin) {
            return $this->formatResponse('L\'heure de début doit être inférieure à l\'heure de fin.', null, true, 400);
        }

        $existSession = Session::where([
            'cours_id' => $request->cours,
            'date_session' => $request->date_session,
            'h_debut' => $request->heure_debut,
            'h_fin' => $request->heure_fin
        ])->first();
    
        if ($existSession) {
            return $this->formatResponse('Une session de cours similaire existe déjà au même moment avec le même prof.', null, true, 400);
        }

        $existingSession = Session::where([
            'cours_id' => $request->cours,
            'date_session' => $request->date_session,
        ])->where(function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('h_debut', '<', $request->heure_fin)
                    ->where('h_fin', '>', $request->heure_debut);
            })->orWhere(function ($query) use ($request) {
                $query->where('h_debut', '>', $request->heure_debut)
                    ->where('h_debut', '<', $request->heure_fin);
            });
        })->first();
    
        if ($existingSession) {
            return $this->formatResponse('Une session de cours existe deja dans cette intervalle.', null, true, 400);
        }

        $salleDispo = Session::where([
            'salle_id' => $request->salle,
            'date_session' => $request->date_session,
        ])->where(function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('h_debut', '<', $request->heure_fin)
                    ->where('h_fin', '>', $request->heure_debut);
            })->orWhere(function ($query) use ($request) {
                $query->where('h_debut', '>', $request->heure_debut)
                    ->where('h_debut', '<', $request->heure_fin);
            });
        })->first();
    
        if ($salleDispo) {
            return $this->formatResponse('La salle est déjà occupée à la même date et heure.', null, true, 400);
        }

        $profDispo=Session::where([
            'date_session'=>$request->date_session
        ])->first();

        if ($cours->heure_restant <= 0) {
            return $this->formatResponse('L\'heure restante pour ce cours est épuisée.', null, true, 400);
        }
        $sess=Session::where('date_session',$request->date_session)->where('cours_id',$request->cours)->get();
        foreach ($sess as  $value) {
            if ($request->cours) {
                
            }
        }
        
        $sess=new Session;
        $sess->date_session = $request->date_session;
        $sess->h_debut = $request->heure_debut;
        $sess->h_fin = $request->heure_fin;
        $sess->nombre_heure = $request->heure_fin - $request->heure_debut;
        if ($cours->heure_restant <= $sess->nombre_heure) {
            return $this->formatResponse('L\'heure restante pour ce cours est inférieur à la durée de la session !', null, true, 400);
        }
        $sess->type = $request->type_session;
        if ($sess->type === "presentiel") {
            $sess->salle_id = $request->salle;
            $salle = Salle::where('id', $request->salle)->first();
            if ($salle->nbre_places < $classe->effectif) {
                return $this->formatResponse('Cette salle ne peut contenir l\'effectif de la classe.', null, true, 400);
            }
        }
        $sess->cours_id = $request->cours;
        $sess->save();

        $cours->heure_restant -= $sess->nombre_heure;
        $cours->save();

        // DB::statement("UPDATE cours set heure_restant = heure_restant -$sess->nombre_heure where id =$cours->id");
        return $this->formatResponse('La séssion de cours a été planifiée avec succés !', $sess, false,200 );
    }
}
