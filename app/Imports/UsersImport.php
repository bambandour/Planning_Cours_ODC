<?php

namespace App\Imports;

use App\Models\AnneeClasse;
use App\Models\AnneeSemestre;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\User;
use App\Notifications\InscriptionNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel,WithHeadingRow
{
    use \App\Traits\ResponseTrait;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            $user = new User([
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'date_naissance' => date('Y-m-d', strtotime($row['date_naissance'])),
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'role' => $row['role'],
            ]);
            $user->save();
            
            $classe = Classe::where('libelle', $row['classe'])->first();
            $classe_id = $classe->id;
            
            $anneeCourante = AnneeClasse::where('classe_id', $classe_id)->where('etat', 1)->first();
            $userIsAlreadyRegister = Inscription::where('user_id', $user->id)->where('annee_classe_id', $anneeCourante->id)->exists();
            
            if ($userIsAlreadyRegister) {
                DB::rollback();
                // return $this->formatResponse('L\'élève est déjà inscrit dans cette classe pour l\'année scolaire courante.', [], false, 400);
            }
            
            $inscription = new Inscription([
                'user_id' => $user->id,
                'annee_classe_id' => $anneeCourante->id,
            ]);
            $inscription->save();
            $user->notify(new InscriptionNotification());
            
            DB::commit();
                } catch (\Exception $e) {
            DB::rollback();
        }
        // return $this->formatResponse('L\'inscription a été ajoutée avec succès.',[] , true, 200);
    }

}
