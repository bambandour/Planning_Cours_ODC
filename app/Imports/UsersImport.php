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
    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            $classe = Classe::where('libelle', $row['classe'])->first();
            $user = new User([
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'date_naissance' => date('Y-m-d', strtotime($row['date_naissance'])),
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
                'role' => $row['role'],
            ]);
            $user->save(); 
    
            $anneeClasse = AnneeClasse::where('classe_id', $classe->id)->where('etat', 1)->first();
            $anneeSemestre = AnneeSemestre::where('etat', 1)->first();
    
            $user->classes()->attach($classe->id);
            $user->anneeClasses()->attach($anneeClasse->id);
            $user->anneeSemestres()->attach($anneeSemestre->id);
    
            $inscription = new Inscription([
                'user_id' => $user->id,
                'annee_classe_id' => $anneeClasse->id,
                'annee_semestre_id' => $anneeSemestre->id,
            ]);
            $inscription->save();
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->formatResponse('Une erreur est survenue lors de l\'ajout du produit.', [], false, 500, $e->getMessage());
        }
    
        return $this->formatResponse('L\'inscription a été ajoutée avec succès.', [], true, 200);
        // try{
        // $classe = Classe::where(['libelle' => $row["classe"]])->first(); 
        // $anneeClasse=AnneeClasse::where('classe_id',$classe->id)->where('etat',1)->first();
        // $anneeSemestre=AnneeSemestre::where('etat',1)->first();
        // dd($anneeSemestre);
        // $user= new User([
        //     'nom'     => $row["nom"],
        //     'prenom'     => $row["prenom"],
        //     'date_naissance' => date('Y-m-d', strtotime($row["date_naissance"])),
        //     'email'    => $row["email"], 
        //     'password' => Hash::make($row["password"]),
        //     'role'    => $row["role"], 
        //     'classe'=>$row["classe"],
        // ]);
        // $user->classes()->attach('');
        // DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $this->formatResponse('Une erreur est survenue lors de l\'ajout du produit.', [], false, 500,$e->getMessage());
        // }

        // $inscription=new Inscription;
        // return ;

    }
}
