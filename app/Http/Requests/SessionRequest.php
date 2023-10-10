<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "date_session"=>"required|date",
            "h_debut"=>"required|min:8|max:15",
            "h_fin"=>"required|min:9|max:16",
            "salle_id"=>"numeric|exists:salles,id",
            "cours_id"=>"numeric|exists:cours,id",
            "type"=>"required|in:presentiel|online"
        ];
    }
}
