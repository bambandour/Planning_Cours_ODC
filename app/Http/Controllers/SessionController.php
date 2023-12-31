<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Http\Resources\SalleResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Models\Absence;
use App\Models\AnneeClasse;
use App\Models\AnneeScolaire;
use App\Models\AnneeSemestre;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\Demande;
use App\Models\Inscription;
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

    public function getSessionsByRole($role,$user,){
        $us=User::where('id',$user)->where('role',$role)->first();
        if ($us->role==='RP') {
            $session=Session::all();
            $sal=Salle::all();
            $salle=SalleResource::collection($sal);
            $data=SessionResource::collection($session);
            $sessions=[
                "session"=>$data,
                "salle"=>$salle
            ];
            return $this->formatResponse("Liste des sessions de cours planifiés", $sessions, true, Response::HTTP_OK);
        }else {
            if ($us->role==='attache'){
                $session=Session::all();
                $data=SessionResource::collection($session);
                $sal=Salle::all();
                $salle=SalleResource::collection($sal);
                $sessions=[
                    "session"=>$data,
                    "salle"=>$salle
                ];
                return $this->formatResponse("Liste des sessions de cours planifiés", $sessions, true, Response::HTTP_OK);
            }
            else {
                if ($us->role==='professeur') {
                    $sal=Salle::all();
                    $salle=SalleResource::collection($sal);
                    $data=$this->getSessionByProf($us->id);
                    $sessions=[
                        "session"=>$data,
                        "salle"=>$salle
                    ];
                    return $this->formatResponse("Liste des sessions de cours planifiés", $sessions, true, Response::HTTP_OK);
                }else {
                    if ($us->role==='etudiant') {
                        $sal=Salle::all();
                        $salle=SalleResource::collection($sal);
                        $data=$this->getSessionsByUser($us->id);
                        $sessions=[
                            "session"=>$data,
                            "salle"=>$salle
                        ];
                        return $this->formatResponse("Liste des sessions de cours planifiés", $sessions, true, Response::HTTP_OK);
                    }
                }
            }
        }
        // return $sessions;
    }
    public function store(Request $request){
        $cours=Cours::where('id',$request->cours)->first();
        $classeAnnee=Cours::where('annee_classe_id',$cours->annee_classe_id)->first();
        $classe=AnneeClasse::where('id',$classeAnnee->annee_classe_id)->where('etat',1)->first();

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
        if($cours->heure_planifie===$cours->heure_globale){
            return $this->formatResponse('L\'heure globale pour ce cours est épuisée.', null, true, 400);
        }
        
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
            if ($salle->nbre_places < $classe->effectif) {
                return $this->formatResponse('Cette salle ne peut contenir l\'effectif de la classe.', null, true, 400);
            }
        }
        $sess->cours_id = $request->cours;
        $sess->save();
        $course=Session::where('id',$sess->id)->first();
        $classeOpen=Cours::where('id',$course->cours_id)->first();
        $etudiants=Inscription::where('annee_classe_id',$classeOpen->annee_classe_id)->pluck('id');
        foreach ($etudiants as  $etudiant) {
            Absence::create([
                'inscription_id'=> $etudiant,
                'session_id'=>$sess->id,
                'etat'=>false
            ]);
        }
        $cours->heure_planifie+=$hours;
        $cours->heure_restant-=$hours;
        $cours->save();
        return $this->formatResponse('La séssion de cours a été planifiée avec succés !', $sess, false,200 );
    }
    public function getSessionByProf($profId) {
        $prof = User::where('id', $profId)->where('role', 'professeur')->first();
        if (!$prof) {
            return $this->formatResponse('le prof n\'existe pas ! ' , [], true,201 ); 
        }
        $userModules = UserModule::where('user_id', $prof->id)->get();
        if ($userModules->isEmpty()) {
            return $this->formatResponse('le module associé au professeur n\'existe pas ! ' , [], true,201 );
        }
        $cours=Cours::whereIn('user_module_id', $userModules->pluck('id'))->get();
        $sessions =Session::whereIn('cours_id', $cours->pluck('id'))->get();
        $data=SessionResource::collection($sessions);
        return  $data;
    }
    public function getSessionsByUser($userId) {
        $user = User::where('id', $userId)->first();
        if (!$user) {
            return $this->formatResponse('l\'etudiant n\'existe pas ! ' , [], true,201 );
        }
        $registration=Inscription::where('user_id', $user->id)->first();
        $cours=Cours::where('annee_classe_id',$registration->annee_classe_id)->get();
        $sessions = [];
        foreach ($cours as $value) {
            $session = Session::where('cours_id',$value->id)->first();
            if ($session) {
                $sessions[] = $session;
            }
        }
        $data=SessionResource::collection($sessions);
        return $data;
    }
    public function cancelSession($session_id){
        $session = Session::where('etat',0)->find($session_id);
        if (!$session) {
            return $this->formatResponse('La session de cours n\'existe pas.', null, true, 404);
        }
        $currentDate = now();
        $sessionDate = Carbon::parse($session->date_session . ' ' . $session->h_debut);
        if ($currentDate >= $sessionDate) {
            return $this->formatResponse('Vous ne pouvez pas annuler une session après la date et l\'heure de début prévues.', null, true, 400);
        }
        $demande=Demande::where('session_id',$session->id)->first();
        $demande->statut="valider";
        $demande->save();
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
        $absence =Absence::where('session_id',$session)->first();
        
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
        $emargement=Absence::where('session_id',$session_id)->get();
        foreach ($emargement as  $emarg) {
            if ($emarg->etat == true) {
                return $this->formatResponse('La session de cours ne peut etre invalidée !', null, true, 404);
            }
        }
        $currentDateTime = now();
        $sessionEndDateTime = Carbon::parse($session->date_session . ' ' . $session->h_fin);
        $session->etat = false;
        $session->save();
        return $this->formatResponse('La session de cours a été invalidée avec succès.', null, false, 200);
    }
    public function emargement(Request $request, $user_id) {
        $eleve=Inscription::where('user_id',$user_id)->first();
        $session=Session::where('id', $request->session)->first();
        $absence=Absence::where('inscription_id',$eleve->id)
                            ->where('session_id',$session->id)->first();
        Absence::find($absence->id)->update(['etat'=> true]);
        return $this->formatResponse('Votre émargement a été pris en compte !', null, false, 200);
    }
}
