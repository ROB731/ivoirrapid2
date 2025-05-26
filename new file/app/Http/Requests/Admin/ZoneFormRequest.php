<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Zone;

class ZoneFormRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Règles de validation.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'Commune' => 'required|string|max:100',
            'PlageZone' => [
    'required',
    'string',
    'regex:/^\d{3}-\d{3}$/',
    function ($attribute, $value, $fail) {
        [$start, $end] = explode('-', $value);
        $start = intval($start);
        $end = intval($end);

        // Récupérer l'ID de la zone en cours de modification
        $currentZoneId = $this->route('zone'); // Si votre route a un paramètre 'zone'

        // Vérifier si la plage existe déjà dans une autre zone
        $zones = Zone::where('id', '!=', $currentZoneId)->get(); // Exclure la zone actuelle

        foreach ($zones as $zone) {
            [$existingStart, $existingEnd] = explode('-', $zone->PlageZone);
            $existingStart = intval($existingStart);
            $existingEnd = intval($existingEnd);

            if (
                ($start >= $existingStart && $start <= $existingEnd) || 
                ($end >= $existingStart && $end <= $existingEnd) || 
                ($start <= $existingStart && $end >= $existingEnd)
            ) {
                $fail('La plage de zone chevauche une plage existante : ' . $zone->PlageZone);
                return;
            }
        }
    }
],
            'NomZone' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $plageZone = request('PlageZone');
                    if ($plageZone) {
                        [$start, $end] = explode('-', $plageZone);
                        $start = intval($start);
                        $end = intval($end);

                        // Vérifier si le nombre de noms de zones correspond à la plage
                        if (count($value) !== ($end - $start + 1)) {
                            $fail("Le nombre de noms de zone doit correspondre à la plage : {$plageZone}.");
                        }
                    }
                }
            ],
            'NomZone.*' => 'required|string|max:100',
            'CoursierName' => 'required|array|min:1',
            'CoursierName.*' => 'required|string|max:100',
            'CoursierCode' => 'required|array|min:1',
            'CoursierCode.*' => 'required|string|max:100',
            'CoursierPhone' => 'required|array|min:1',
            'CoursierPhone.*' => 'required|string|max:100',
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'PlageZone.regex' => 'La plage de zone doit être au format 001-999.',
            'NomZone.required' => 'Au moins un nom de zone est requis.',
            'NomZone.*.required' => 'Chaque NomZone est requis.',
            'CoursierName.*.required' => 'Chaque CoursierName est requis.',
            'CoursierCode.*.required' => 'Chaque CoursierCode est requis.',
            'CoursierPhone.*.required' => 'Chaque CoursierPhone est requis.',
        ];
    }
}
