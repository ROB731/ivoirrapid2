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
                                'Commune' => 'required|string|max:200',
                                'PlageZone' => [
                                    'required',
                                    'string',
                                    'regex:/^\d{3}-\d{3}$/',
                                    function ($attribute, $value, $fail) {
                                        [$start, $end] = explode('-', $value);
                                        $start = intval($start);
                                        $end = intval($end);

                                        //  Vérification de l'ID existant
                                        // $currentZoneId = $this->route('zone') ?? null;
                                        $currentZoneId = request()->route('zone') ?? null;


                                        // Vérifier si la plage existe déjà
                                        $zones = Zone::where('id', '!=', $currentZoneId)->get();

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
                                'NomZone' => 'required|array|min:1',
                                'NomZone.*' => 'required|string|max:255',
                                'code_coursier' => 'required|string|max:100',
                                'libelle_detail_zone' => 'nullable|array|min:1',
                                'libelle_detail_zone.*' => 'nullable|string|min:1',

                                'libelle_zone' => 'nullable|string|min:1',
                                'NumeroZone' => 'required|array|min:1',
                                'NumeroZone.*' => 'required|string|max:255',
                                // 'libelle_detail_zone' => 'nullable|min:1', //Au depart un array
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
                    // 'CoursierName.*.required' => 'Chaque CoursierName est requis.',
                    // 'CoursierCode.*.required' => 'Chaque CoursierCode est requis.',
                    // 'CoursierPhone.*.required' => 'Chaque CoursierPhone est requis.',
                ];
            }


}
