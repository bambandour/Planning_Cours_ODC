<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenceResource extends JsonResource
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
            "session"=>SessionResource::make($this->session),
            "inscription"=>InscriptionResource::make($this->inscription),
            "etat"=>$this->etat
        ];
    }
}
