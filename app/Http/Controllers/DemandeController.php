<?php

namespace App\Http\Controllers;

use App\Http\Resources\DemandeResource;
use App\Models\Cours;
use App\Models\Demande;
use App\Models\Session;
use App\Models\User;
use App\Models\UserModule;
use App\Notifications\NewAnnulationDemande;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    use \App\Traits\ResponseTrait;
    public function index(){
        $demande=Demande::where('statut','pending')->get();
        $data= DemandeResource::collection($demande);
        return $this->formatResponse('La liste des demandes en attente !.', $data, true, 200); 
    }
    public function store(Request $request){
        // $session=Session::where("id", $request->session)->first();
        // $cours=Cours::where("id", $session->cours->id)->first();
        // $userModule=UserModule::where("id", $cours->user_module_id)->first();
        // $prof=User::where("id", $userModule->user_id)->first();
        // dd($prof);
        $demande = Demande::create([
            "session_id"=> $request->session,
            "motif"=> $request->motif,
        ]);
        // $user=User::where('role','RP')->first();
        // $user->notify(new NewAnnulationDemande($demande));
        return $this->formatResponse('La demande a été faite avec succées !.', $demande, true, 200);
    }

    // public validtedReq
}
