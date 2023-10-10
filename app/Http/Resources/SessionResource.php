<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "date"=>$this->date_session,
            "heure_debut"=>$this->h_debut,
            "heure_fin"=>$this->h_fin,
            "duree"=>$this->nombre_heure,
            "cours"=>CoursResource::make($this->cours),
            "type_session"=>$this->type,
            "salle"=>$this->salle->libelle,
            "etat"=>$this->etat,
            // "classes"=>ClasseResource::collection($this->annee_classes),
        ];
    }
}
