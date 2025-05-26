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
        $rules = [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'telephone' => 'required|regex:/^\d{10}$/|unique:coursiers,telephone',
            'email' => 'nullable|email|max:255|unique:coursiers,email',
            'code' => 'nullable|string|max:50',
            'numero_de_permis' => 'nullable|string|max:50',
            'date_de_validite_du_permis' => 'nullable|date',
            'categorie_du_permis' => 'nullable|string|max:20',
            'numero_de_cni' => 'nullable|string|max:50|unique:coursiers,numero_de_cni',
            'date_de_validite_de_la_cni' => 'nullable|date',
            'numero_de_la_cmu' => 'nullable|string|max:50|unique:coursiers,numero_de_la_cmu',
            'date_de_validite_de_la_cmu' => 'nullable|date',
            'date_de_naissance' => 'nullable|date',
            'groupe_sanguin' => 'nullable|string|max:5',
            'date_de_debut_du_contrat' => 'nullable|date',
            'date_de_fin_du_contrat' => 'nullable|date|after_or_equal:date_de_debut_du_contrat',
            'adresse' => 'nullable|string|max:255',
            'contact_urgence' => 'nullable|string|max:10|regex:/^\d{10}$/',
            'affiliation_urgence' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
        ];

        return $rules;
    }
}
