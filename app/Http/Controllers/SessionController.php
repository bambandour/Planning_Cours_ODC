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
use Carbon\Carbon;
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

        $isProfDispo = Session::join('cours', 'sessions.cours_id', '=', 'cours.id')
        ->join('user_modules', 'cours.user_module_id', '=', 'user_modules.id')
        // ->where('user_modules.user_id', $prof->id)
        ->where('sessions.date_session', $request->date_session)
        ->where(function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('sessions.h_debut', '<', $request->heure_fin)
                    ->where('sessions.h_fin', '>', $request->heure_debut);
            })->orWhere(function ($query) use ($request) {
                $query->where('sessions.h_debut', '>', $request->heure_debut)
                    ->where('sessions.h_debut', '<', $request->heure_fin);
            });
        })->first();

        if ($isProfDispo) {
            return $this->formatResponse('Le professeur est déjà occupé à la même date et heure.', null, true, 400);
        }

        if ($cours->heure_restant <= 0) {
            return $this->formatResponse('L\'heure restante pour ce cours est épuisée.', null, true, 400);
        }
        // $sess=Session::where('date_session',$request->date_session)->where('cours_id',$request->cours)->get();
        // foreach ($sess as  $value) {
        //     if ($request->cours) {        
        //     }
        // }
        $sess=new Session;
        $sess->date_session = $request->date_session;
        $sess->h_debut = $request->heure_debut;
        $sess->h_fin = $request->heure_fin;
        list($h_debut_heures, $h_debut_minutes) = explode(':', $sess->h_debut);
        list($h_fin_heures, $h_fin_minutes) = explode(':', $sess->h_fin);
        $heures_diff = $h_fin_heures - $h_debut_heures;
        $minutes_diff = $h_fin_minutes - $h_debut_minutes;
        if ($minutes_diff < 0) {
            $heures_diff--;
            $minutes_diff += 60;
        }
        $hours = $heures_diff + ($minutes_diff / 60);
        $sess->nombre_heure = $hours;

        if ($cours->heure_restant <= $sess->nombre_heure) {
            return $this->formatResponse('L\'heure restante pour ce cours est inférieur à la durée de la session !', null, true, 400);
        }
        $sess->type = $request->type_session;
        if ($sess->type === "presentiel") {
            $sess->salle_id = $request->salle;
            $salle = Salle::where('id', $request->salle)->first();
            // if ($salle->nbre_places < $classe->effectif) {
            //     return $this->formatResponse('Cette salle ne peut contenir l\'effectif de la classe.', null, true, 400);
            // }
        }
        $sess->cours_id = $request->cours;
        $sess->save();
        return $this->formatResponse('La séssion de cours a été planifiée avec succés !', $sess, false,200 );
    }

    public function getSessionByProf($profId) {
        $prof = User::where('id', $profId)->where('role', 'professeur')->first();
        if (!$prof) {
            // return []; 
        return $this->formatResponse('le prof n\'existe pas ! ' , [], true,201 );
        }
        $userModule = UserModule::where('user_id', $prof->id)->first();
        if (!$userModule) {
            return $this->formatResponse('le module associé au professeur n\'existe pas ! ' , [], true,201 );
        }
        $cours = Cours::where('user_module_id', $userModule->id)->get();
        $sessions = [];
        foreach ($cours as $value) {
            $session = Session::where('cours_id', $value->id)->first();
            if ($session) {
                $sessions[] = $session;
            }
        }
        $data=SessionResource::collection($sessions);
        return $this->formatResponse('La liste des sessions de cours de Monsieur '.$prof->nom.' !', $data, false,200 );
    }

    public function cancelSession($session_id){
        $session = Session::find($session_id);
        if (!$session) {
            return $this->formatResponse('La session de cours n\'existe pas.', null, true, 404);
        }
        $currentDate = now();
        $sessionDate = Carbon::parse($session->date_session . ' ' . $session->h_debut);
        if ($currentDate >= $sessionDate) {
            return $this->formatResponse('Vous ne pouvez pas annuler une session après la date et l\'heure de début prévues.', null, true, 400);
        }
        $cours = Cours::find($session->cours_id);
        $cours->heure_restant += $session->nombre_heure;
        $cours->save();
        $session->delete();
        return $this->formatResponse('La session de cours a été annulée avec succès.', null, false, 200);
    }

    public function validateSession($session_id) {
        $session = Session::find($session_id);
        if (!$session) {
            return $this->formatResponse('La session de cours n\'existe pas.', null, true, 404);
        }
        $currentDateTime = now();
        $sessionEndDateTime = Carbon::parse($session->date_session . ' ' . $session->h_fin);
        if ($currentDateTime > $sessionEndDateTime) {
            $cours = Cours::find($session->cours_id);
            $cours->heure_restant -= $session->nombre_heure;
            $cours->save();
            $session->etat = true;
            $session->save();
            return $this->formatResponse('La session de cours a été validée avec succès.', null, false, 200);
        } else {
            return $this->formatResponse('Vous ne pouvez pas valider une session avant qu\'elle ne soit terminée.', null, true, 400);
        }
    }
    
    public function invalidateSession($session_id) {
        $session = Session::find($session_id);
        if (!$session) {
            return $this->formatResponse('La session de cours n\'existe pas.', null, true, 404);
        }
        $session->etat = false;
        $session->save();
        return $this->formatResponse('La session de cours a été invalidée avec succès.', null, false, 200);
    }
    
    
    
}
