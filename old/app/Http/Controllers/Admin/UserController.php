<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserFormRequest;
use App\Models\Coursier;
use App\Models\Destinataire;
use App\Models\Pli;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index()
{
    $users = User::paginate(10); // Paginer les utilisateurs par 10
    $destinataires = Destinataire::paginate(10); // Paginer les destinataires par 10
    $plis = Pli::paginate(10); // Paginer les plis par 10
    $coursiers = Coursier::paginate(10); // Paginer les coursiers par 10

    $totalDestinataires = Destinataire::count();
    $totalUsers = User::count();
    $totalPlis = Pli::count();
    $totalCoursiers = Coursier::count();

    return view('admin.user.index', compact(
        'users', 
        'totalUsers', 
        'totalDestinataires', 
        'destinataires', 
        'totalPlis', 
        'plis', 
        'totalCoursiers', 
        'coursiers'
    ));
}

    public function addUser()
    {
        
        return view('admin.user.addUser');
    }

    public function store(UserFormRequest $request)
{
    $data = $request->validated();

    try {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->abreviation = $data['abreviation'];
        $user->Telephone = $data['Telephone'];
        $user->Cellulaire = $data['Cellulaire'];
        $user->Compte_contribuable = $data['Compte_contribuable'];
        $user->RCCM = $data['RCCM'];
        $user->Direction_1_Nom_et_Prenoms = $data['Direction_1_Nom_et_Prenoms'];
        $user->Direction_1_Contact = $data['Direction_1_Contact'];
        $user->Direction_2_Nom_et_Prenoms = $data['Direction_2_Nom_et_Prenoms'];
        $user->Direction_2_Contact = $data['Direction_2_Contact'];
        $user->Direction_3_Nom_et_Prenoms = $data['Direction_3_Nom_et_Prenoms'];
        $user->Direction_3_Contact = $data['Direction_3_Contact'];
        $user->Adresse = $data['Adresse'];
        $user->Commune = $data['Commune'];
        $user->Quartier = $data['Quartier'];
        $user->Rue = $data['Rue'];
        $user->Zone = $data['Zone'];
        $user->Autre = $data['Autre'];

        $user->save();

        return view('admin.dashboard')->with('success', 'Utilisateur ajouté avec succès');
    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            $errorField = $this->getDuplicateField($e->errorInfo[2]);
            return redirect()->back()->withErrors([$errorField => 'Cet client existe déjà.']);
        }
        throw $e; // Remet l'exception pour toute autre erreur
    }
}

/**
 * Fonction pour obtenir le champ qui a provoqué l'erreur unique
 */
private function getDuplicateField($errorMessage)
{
    $fieldMapping = [
        'users_name_unique' => 'name',
        'users_email_unique' => 'email',
        'users_abreviation_unique' => 'abreviation',
        'users_Telephone_unique' => 'Telephone',
        'users_Cellulaire_unique' => 'Cellulaire',
        'users_Compte_contribuable_unique' => 'Compte_contribuable',
        'users_RCCM_unique' => 'RCCM',
        'users_Direction_1_Nom_et_Prenoms_unique' => 'Direction_1_Nom_et_Prenoms',
    ];

    foreach ($fieldMapping as $constraint => $field) {
        if (str_contains($errorMessage, $constraint)) {
            return $field;
        }
    }

    return 'unknown_field';
}



public function edit($user_id){
    $user = User::find($user_id);
    return view('admin.user.edit', compact('user'));
}

public function update(UserFormRequest $request, $user_id){
    $data = $request->validated();

    try {
        $user = User::find($user_id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->abreviation = $data['abreviation'];
        $user->Telephone = $data['Telephone'];
        $user->Cellulaire = $data['Cellulaire'];
        $user->Compte_contribuable = $data['Compte_contribuable'];
        $user->RCCM = $data['RCCM'];
        $user->Direction_1_Nom_et_Prenoms = $data['Direction_1_Nom_et_Prenoms'];
        $user->Direction_1_Contact = $data['Direction_1_Contact'];
        $user->Direction_2_Nom_et_Prenoms = $data['Direction_2_Nom_et_Prenoms'];
        $user->Direction_2_Contact = $data['Direction_2_Contact'];
        $user->Direction_3_Nom_et_Prenoms = $data['Direction_3_Nom_et_Prenoms'];
        $user->Direction_3_Contact = $data['Direction_3_Contact'];
        $user->Adresse = $data['Adresse'];
        $user->Commune = $data['Commune'];
        $user->Quartier = $data['Quartier'];
        $user->Rue = $data['Rue'];
        $user->Zone = $data['Zone'];
        $user->Autre = $data['Autre'];

        $user->update();

        return view('admin.dashboard')->with('success', 'Utilisateur modifié avec succès');
    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            $errorField = $this->getDuplicateField($e->errorInfo[2]);
            return redirect()->back()->withErrors([$errorField => 'Cet client existe déjà.']);
        }
        throw $e; // Remet l'exception pour toute autre erreur
    }
}

public function destroy($user_id){
    $user = user::find($user_id);
    if($user){
        $user->delete();
        return view('admin.dashboard')->with('success', 'Utilisateur supprimé avec succès');
    }
    else{
        return view('admin.dashboard')->with('error', 'Utilisateur introuvable');
    }
}
}
