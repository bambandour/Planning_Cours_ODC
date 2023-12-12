<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbsenceResource;
use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    use \App\Traits\ResponseTrait;
    public function index(){
        $absence = Absence::all();
        $data=AbsenceResource::collection($absence);
        return $this->formatResponse('La liste des émargements !', $data, false, 201);
    }
    public function getEmargementsBySession($session){
        $absence = Absence::where('session_id', $session)->get();
        dd($absence);
        
    }
}
