<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CoursierFormRequest extends FormRequest
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
        // Récupérer l'ID du coursier s'il est présent dans la route
        $coursierId = $this->route('coursier_id');

        return [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'telephone' => [
                'required',
                'regex:/^\d{10}$/',
                $coursierId ? 'unique:coursiers,telephone,' . $coursierId : 'unique:coursiers,telephone',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                $coursierId ? 'unique:coursiers,email,' . $coursierId : 'unique:coursiers,email',
            ],
            'numero_de_cni' => [
                'nullable',
                'string',
                'max:50',
                $coursierId ? 'unique:coursiers,numero_de_cni,' . $coursierId : 'unique:coursiers,numero_de_cni',
            ],
            'numero_de_la_cmu' => [
                'nullable',
                'string',
                'max:50',
                $coursierId ? 'unique:coursiers,numero_de_la_cmu,' . $coursierId : 'unique:coursiers,numero_de_la_cmu',
            ],
            'code' => 'nullable|string|max:50',
            'numero_de_permis' => 'nullable|string|max:50',
            'date_de_validite_du_permis' => 'nullable|date',
            'categorie_du_permis' => 'nullable|string|max:20',
            'date_de_validite_de_la_cni' => 'nullable|date',
            'date_de_validite_de_la_cmu' => 'nullable|date',
            'date_de_naissance' => 'nullable|date',
            'groupe_sanguin' => 'nullable|string|max:5',
            'date_de_debut_du_contrat' => 'nullable|date',
            'date_de_fin_du_contrat' => 'nullable|date|after_or_equal:date_de_debut_du_contrat',
            'adresse' => 'nullable|string|max:255',
            'contact_urgence' => [
                'nullable',
                'string',
                'regex:/^\d{10}$/',
                $coursierId ? 'unique:coursiers,contact_urgence,' . $coursierId : 'unique:coursiers,contact_urgence',
            ],
            'affiliation_urgence' => 'nullable|string|max:255',
            'zone_count' => 'required|integer',
            // 'zones' => 'required|array',
            // 'zones.*' => 'required|string|max:255',
              'zones' => 'array',
            'zones.*' => 'max:255',
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     */
    public function messages()
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'telephone.required' => 'Le numéro de téléphone est requis.',
            'telephone.regex' => 'Le numéro de téléphone doit comporter exactement 10 chiffres.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre coursier.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cet email est déjà associé à un autre coursier.',
            'numero_de_cni.unique' => 'Ce numéro de CNI est déjà enregistré.',
            'numero_de_la_cmu.unique' => 'Ce numéro de CMU est déjà attribué.',
            'contact_urgence.regex' => 'Le numéro de contact d\'urgence doit comporter exactement 10 chiffres.',
            'contact_urgence.unique' => 'Ce contact d\'urgence est déjà utilisé.',
            'date_de_fin_du_contrat.after_or_equal' => 'La date de fin du contrat doit être égale ou postérieure à la date de début du contrat.',
            'zone.array' => 'La zone doit être un tableau.',
            'zone.*.string' => 'Chaque zone doit être une chaîne de caractères valide.',
        ];
    }
}
