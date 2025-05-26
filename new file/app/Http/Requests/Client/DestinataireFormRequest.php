<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestinataireFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

   /*  public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('destinataires')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
            'email' => 'required|email',
            'telephone' => 'required|digits:10',
            'contact' => 'required|digits:10',
            'adresse' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'autre' => 'nullable|string|max:255',
        ];
    } */

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Règle pour vérifier l'unicité du nom par utilisateur, excluant l'enregistrement actuel
                Rule::unique('destinataires')
                    ->where(function ($query) {
                        return $query->where('user_id', auth()->id());
                    })
                    ->ignore($this->route('destinataire_id')), // Exclure l'enregistrement actuel
            ],
            'email' => 'required|email',
            'telephone' => 'required|digits:10',
            'contact' => 'required|digits:10',
            'adresse' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'autre' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Vous avez déjà créé un destinataire avec ce nom.',
        ];
    }
}
