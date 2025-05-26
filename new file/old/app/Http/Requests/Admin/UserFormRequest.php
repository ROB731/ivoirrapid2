<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    public function authorize()
    {
        // Autoriser toutes les requêtes (vous pouvez implémenter votre logique d'autorisation ici)
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'abreviation' => 'required|string|max:50',
            'Telephone' => 'nullable|string|max:20',
            'Cellulaire' => 'nullable|string|max:20',
            'Compte_contribuable' => 'nullable|string|max:50',
            'RCCM' => 'nullable|string|max:50',
            'Direction_1_Nom_et_Prenoms' => 'nullable|string|max:255',
            'Direction_1_Contact' => 'nullable|string|max:20',
            'Direction_2_Nom_et_Prenoms' => 'nullable|string|max:255',
            'Direction_2_Contact' => 'nullable|string|max:20',
            'Direction_3_Nom_et_Prenoms' => 'nullable|string|max:255',
            'Direction_3_Contact' => 'nullable|string|max:20',
            'Adresse' => 'nullable|string|max:255',
            'Commune' => 'nullable|string|max:100',
            'Quartier' => 'nullable|string|max:100',
            'Rue' => 'nullable|string|max:100',
            'Zone' => 'nullable|string|max:100',
            'Autre' => 'nullable|string|max:255',
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'Le nom est obligatoire.',
        'name.unique' => 'Ce nom est déjà utilisé, veuillez en choisir un autre.',
        
        'email.required' => 'L\'email est obligatoire.',
        'email.unique' => 'Cet email est déjà enregistré.',
        
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        
        'abreviation.required' => 'L\'abréviation est obligatoire.',
        'abreviation.unique' => 'Cette abréviation est déjà utilisée.',
        
        'Telephone.unique' => 'Ce numéro de téléphone est déjà enregistré.',
        
        'Cellulaire.unique' => 'Ce numéro de cellulaire est déjà enregistré.',
        
        'Compte_contribuable.unique' => 'Ce numéro de contribuable est déjà enregistré.',
        
        'RCCM.unique' => 'Ce numéro RCCM est déjà enregistré.',
        
        'Direction_1_Nom_et_Prenoms.unique' => 'Cette direction est déjà enregistrée.',
    ];
}

}
