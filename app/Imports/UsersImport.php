<?php

namespace App\Imports;

use App\Models\AnneeClasse;
use App\Models\AnneeSemestre;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\User;
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
    // public function model(array $row)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $user = new User([
    //             'nom' => $row['nom'],
    //             'prenom' => $row['prenom'],
    //             'date_naissance' => date('Y-m-d', strtotime($row['date_naissance'])),
    //             'email' => $row['email'],
    //             'password' => Hash::make($row['password']),
    //             'role' => $row['role'],
    //         ]);
    //         // $user->save(); 
            
    //         $classe = Classe::where('libelle', $row['classe'])->first();
    //         $anneeClasse = AnneeClasse::where('classe_id', $classe->id)->where('etat', 1)->pluck('id');
    //         // dd($anneeClasse);
    //         // $anneeSemestre = AnneeSemestre::where('etat', 1)->first();
            
    //         // dd($user);
    //         $user->classes()->attach($anneeClasse);
    //         return $user;
    //         // $user->anneeClasses()->attach($anneeClasse->id);
    //         // $user->anneeSemestres()->attach($anneeSemestre->id);
    
    //         // $inscription = new Inscription([
    //         //     'user_id' => $user->id,
    //         //     'annee_classe_id' => $anneeClasse->id,
    //         //     'annee_semestre_id' => $anneeSemestre->id,
    //         // ]);
    //         // $inscription->save();
    
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return $this->formatResponse('Une erreur est survenue lors de l\'ajout du produit.', [], false, 500, $e->getMessage());
    //     }
    
    //     return $this->formatResponse('L\'inscription a été ajoutée avec succès.', [], true, 200);
       

    // }
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
            
            // $anneeClasse = AnneeClasse::where('classe_id', $classe_id)->where('etat', 1)->first();
            // $annee_classe_id = $anneeClasse->id;

            $inscription = new Inscription([
                'user_id' => $user->id,
                'annee_classe_id' => $anneeCourante->id,
            ]);
            $inscription->save();

            DB::commit();
                } catch (\Exception $e) {
                // return $this->formatResponse('Une erreur est survenue lors de l\'inscription.', [], false, 500, $e->getMessage());
            DB::rollback();
        }
        // return $this->formatResponse('L\'inscription a été ajoutée avec succès.',[] , true, 200);
    }

}
