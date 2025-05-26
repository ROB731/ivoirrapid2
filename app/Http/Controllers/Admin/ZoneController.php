<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ZoneFormRequest;
use App\Models\Zone;
use App\Models\ZoneDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{

    public function index(Request $request)
    {
        // Récupérer le terme de recherche
        $searchCommune = $request->input('search_commune');

        // Si l'utilisateur est admin (role_as == 1)
        if (Auth::user()->role_as == '1') {
            // Admin voit toutes les zones avec possibilité de filtrer par commune
            if ($searchCommune) {
                // Recherche insensible à la casse pour filtrer les communes
                $zones = Zone::whereRaw('LOWER(Commune) LIKE ?', ['%' . strtolower($searchCommune) . '%'])
                             ->with('details')
                             ->paginate(25);
            } else {
                // Récupérer toutes les zones pour l'admin
                // $zones = Zone::with('details')->paginate(25);
              $zones = Zone::with('coursier', 'details')->paginate(25);

            }

            // Retourner la vue pour l'admin
            return view('admin.zone.index', compact('zones'));
        } else {
            // Utilisateur basique : il voit toutes les zones (créées ou modifiées par l'admin)
            // Si un terme de recherche est fourni, filtrer les zones par commune
            if ($searchCommune) {
                // Recherche insensible à la casse pour filtrer les communes
                $zones = Zone::whereRaw('LOWER(Commune) LIKE ?', ['%' . strtolower($searchCommune) . '%'])
                             ->with('details')
                             ->paginate(25);
            } else {
                // Sinon, récupérer toutes les zones sans filtre
                // $zones = Zone::with('details')->paginate(25);
                $zones = Zone::with('coursier', 'details')->paginate(25);

            }

            // Retourner la vue pour l'utilisateur basique
            return view('client.zone.index', compact('zones'));
        }
    }





    public function create()
    {
        return view('admin.zone.create');
    }




    public function show($id)
    {
        // $zone = Zone::with('details')->findOrFail($id); // Utilise la relation 'details'
        // $zone = Zone::with('coursier', 'details')->find($id);
        $zone = Zone::with('coursier', 'details')->findOrFail($id); // Arrête l'exécution si l'ID est introuvable
        return view('admin.zone.show', compact('zone'));
    }


            public function store(ZoneFormRequest $request)
                    {
                        $data = $request->validated(); //  Utilise les données validées

                        //  Vérification des données avant insertion
                        // dd($data); // Supprime après test si tout est correct

                        //  Création de la zone principale
                        $zone = Zone::create([
                            'Commune' => $data['Commune'],
                            'PlageZone' => $data['PlageZone'],
                            'code_coursier' => $data['code_coursier'],
                            'libelle_zone' => $data['libelle_zone'],
                        ]);

                        //  Vérification de `NomZone` avant la boucle
                        foreach ($data['NomZone'] ?? [] as $key => $nomZone) {
                            ZoneDetail::create([
                                'zone_id' => $zone->id,
                                'NomZone' => $nomZone,
                                'libelle_detail_zones' => $data['libelle_detail_zone'][$key] ?? null, //  Sécurisation de l'accès
                                'NumeroZone' => $key,
                            ]);
                        }

                        return redirect('admin/zone')->with('message', 'Zone ajoutée avec succès.');
                    }



        public function edit($zone_id)
            {
                $zone = Zone::with('details')->findOrFail($zone_id);
                return view('admin.zone.edit', compact('zone'));
            }



//         public function update(ZoneFormRequest $request, $zone_id) {
//                     $data = $request->validated();
//                     // Récupérer la zone principale
//                     $zone = Zone::findOrFail($zone_id);
//                         $zone->Commune = $data['Commune'];
//                         $zone->PlageZone = $data['PlageZone'];
//                         $zone->code_coursier = $data['code_coursier'];
//                         $zone->libelle_zone = $data['libelle_zone'];
//                     $zone->save();  // Utilisation de save() pour persister les modifications de zone


//                 // Mettre à jour les détails associés

//                 foreach ($data['NomZone'] as $key => $nomZone) {
//                     // Trouver le détail de la zone en fonction du numéro de zone
//                     $zoneDetail = ZoneDetail::where('zone_id', $zone->id)
//                                             ->where('NumeroZone', $key)
//                                             ->first();  // Recherche le détail existant par zone_id et NumeroZone

//                             if ($zoneDetail) {
//                                 // Si le détail existe, on le met à jour
//                                 $zoneDetail->update([  //Mise à jour --------------------------------------
//                                     // 'NomZone' => $nomZone,
//                                     'NomZone' => $data['NomZone'],
//                                     'NumeroZone' => $data['NumeroZone'],  // On garde le numéro de zone existant
//                                     //  'NumeroZone' => $data['NumeroZone'][$key],  // On garde le numéro de zone existant

//                                     'libelle_detail_zones'=>$data['libelle_detail_zone'],

//                                 ]);
//                              }

//                 else {

//                     if(is_array($data))
//                         {
//                      foreach( $data as $dataCreate)
//                             {
//                                 ZoneDetail::create([
//                                     'zone_id' => $dataCreate['NomZone'],
//                                     'NomZone' => $dataCreate['NomZone'],
//                                     'NumeroZone' => $dataCreate['NumeroZone'],  // On garde le numéro de zone existant
//                                     'libelle_detail_zones'=>$data['libelle_detail_zone'],

//                                 ]);

//                             }
//                         }


//                 }
//     }

//     return redirect('admin/zone')->with('message', 'Plage de zone modifiée avec succès.');
// }



              public function update(ZoneFormRequest $request, $zone_id) {
                        $data = $request->validated();

                        //  Vérification pour éviter l'erreur Undefined array key
                        if (!isset($data['NumeroZone']) || !is_array($data['NumeroZone'])) {
                            return redirect()->back()->with('error', 'Les données de NumeroZone sont invalides.');
                        }

                        //  Récupérer la zone principale
                        $zone = Zone::findOrFail($zone_id);
                        $zone->Commune = $data['Commune'];
                        $zone->PlageZone = $data['PlageZone'];
                        $zone->code_coursier = $data['code_coursier'];
                        $zone->libelle_zone = $data['libelle_zone'];
                        $zone->save(); //  Enregistre les modifications



                        foreach ($data['NomZone'] as $id => $nomZone) {


                                if (!isset($data['NumeroZone'][$id]) || !isset($data['libelle_detail_zone'][$id])) {
                                    continue; //  Ignore cette entrée si l'index est absent
                                }

                                $zoneDetail = ZoneDetail::where('zone_id', $zone->id)
                                                        ->where('id', $id) //  Correction de l'identification par `id`
                                                        ->first();


                                // if ($zoneDetail) {
                                //     $zoneDetail->update([
                                //                 'NomZone' => $nomZone,
                                //                 'NumeroZone' => $data['NumeroZone'][$id],
                                //                 'libelle_detail_zones' => $data['libelle_detail_zone'][$id],
                                //                 ]);

                                // }

                                // else {
                                //     ZoneDetail::create([
                                //         'zone_id' => $zone->id,
                                //         'NomZone' => $nomZone,
                                //         'NumeroZone' => $data['NumeroZone'][$id],
                                //         'libelle_detail_zones' => $data['libelle_detail_zone'][$id],
                                //     ]);
                                // }



                                if ($zoneDetail) {
                                        //  Si la zone existe, on met à jour
                                        $zoneDetail->update([
                                            'NomZone' => $nomZone,
                                            'NumeroZone' => $data['NumeroZone'][$id],
                                            'libelle_detail_zones' => $data['libelle_detail_zone'][$id],
                                        ]);
                                    } else {
                                        //  Création seulement si aucun détail n'existe déjà
                                        ZoneDetail::firstOrCreate(
                                            ['zone_id' => $zone->id,
                                             'NumeroZone' => $data['NumeroZone'][$id]], // Critère unique pour éviter les doublons
                                            [
                                                'NomZone' => $nomZone,
                                                'libelle_detail_zones' => $data['libelle_detail_zone'][$id],
                                            ]
                                        );
                                    }



                            }





                        return redirect('admin/zone')->with('message', 'Plage de zone modifiée avec succès.');
                    }






            public function destroy($zone_id)
            {
                // Trouver la zone à supprimer
                $zone = Zone::findOrFail($zone_id);

                // Supprimer les détails associés à cette zone
                ZoneDetail::where('zone_id', $zone->id)->delete();

                // Supprimer la zone principale
                $zone->delete();

                // Rediriger vers la liste des zones avec un message de succès
                return redirect('admin/zone')->with('message', 'Zone supprimée avec succès.');
            }


            // Sous zone et entreprise







}
