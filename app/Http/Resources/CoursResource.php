<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursResource extends JsonResource
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
            "annee_semestre"=>AnneeSemestreResource::make($this->annee_semestre),
            "annee_classe"=>AnneeClasseResource::make($this->annee_classe),
            "prof_module"=>UserModuleResource::make($this->user_module),
            "heure_globale"=>$this->heure_globale,
            "heure_restant"=>$this->heure_restant,
            "heure_planifie"=>$this->heure_planifie,
            "etat"=>$this->etat,
            // "classes"=>ClasseResource::collection($this->annee_classes),
        ];
    }
}
