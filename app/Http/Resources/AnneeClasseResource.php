<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnneeClasseResource extends JsonResource
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
            "classe"=>ClasseResource::make($this->classe),
            "eleves"=>UserResource::collection($this->whenLoaded('users')),
            "effectif"=>$this->effectif,
        ];
    }
}
