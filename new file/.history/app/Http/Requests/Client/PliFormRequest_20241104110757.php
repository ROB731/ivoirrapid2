<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PliFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            // ID du destinataire et de l'expéditeur
            'destinataire_id' => 'required|exists:destinataires,id',
            'user_id' => 'required|exists:users,id',

            // Informations sur le destinataire
            'destinataire_name' => 'required|string|max:255',
            'destinataire_adresse' => 'required|string|max:255',
            'destinataire_telephone' => 'required|string|max:15',
            'destinataire_email' => 'nullable|email|max:255',
            'destinataire_zone' => 'nullable|string|max:255',
            'destinataire_contact' => 'nullable|string|max:15',
            'destinataire_autre' => 'nullable|string|max:255',

            // Informations sur l'expéditeur (user)
            'user_name' => 'required|string|max:255',
            'user_Adresse' => 'required|string|max:255',
            'user_Telephone' => 'required|string|max:15',
            'user_email' => 'nullable|email|max:255',
            'user_Zone' => 'nullable|string|max:255',
            'user_Cellulaire' => 'nullable|string|max:15',
            'user_Autre' => 'nullable|string|max:255',

            // Informations sur le colis
            'type' => 'required|string|max:255',
            'nombre_de_pieces' => 'required|integer|min:1',
            'reference' => 'required|array', // Cela doit être un tableau
        'reference.*' => 'required|string|max:255', // Chaque référence doit être une chaîne non vide
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'destinataire_id.required' => 'Veuillez sélectionner un destinataire.',
            'destinataire_id.exists' => 'Le destinataire sélectionné est invalide.',
            'user_id.required' => 'L’expéditeur est requis.',
            'user_id.exists' => 'L’expéditeur sélectionné est invalide.',

            // Messages pour les informations sur le destinataire
            'destinataire_name.required' => 'Le nom du destinataire est requis.',
            'destinataire_adresse.required' => 'L’adresse du destinataire est requise.',
            'destinataire_telephone.required' => 'Le téléphone du destinataire est requis.',
            'destinataire_email.email' => 'Veuillez fournir une adresse email valide pour le destinataire.',
            'destinataire_zone.string' => 'La zone du destinataire doit être une chaîne de caractères.',
            'destinataire_contact.string' => 'Le contact alternatif du destinataire doit être une chaîne de caractères.',

            // Messages pour les informations sur l’expéditeur (user)
            'user_name.required' => 'Le nom de l’expéditeur est requis.',
            'user_Adresse.required' => 'L’adresse de l’expéditeur est requise.',
            'user_Telephone.required' => 'Le téléphone de l’expéditeur est requis.',
            'user_email.email' => 'Veuillez fournir une adresse email valide pour l’expéditeur.',
            'user_Zone.string' => 'La zone de l’expéditeur doit être une chaîne de caractères.',
            'user_Cellulaire.string' => 'Le contact alternatif de l’expéditeur doit être une chaîne de caractères.',

            // Messages pour les informations sur le colis
            'type.required' => 'Le type de pli est requis.',
            'reference'=> 'La référence du plis est requise.',
            'nombre_de_pieces.required' => 'Le nombre de pièces est requis.',
            'nombre_de_pieces.integer' => 'Le nombre de pièces doit être un nombre entier.',
            'nombre_de_pieces.min' => 'Le nombre de pièces doit être d\'au moins 1.',
        ];
    }
}
