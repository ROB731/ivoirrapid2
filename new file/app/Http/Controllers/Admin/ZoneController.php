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
  /*   public function index()
    {
        // Récupérer toutes les zones avec leurs détails
        $zones = Zone::with('details')->paginate(25);
        return view('admin.zone.index', compact('zones'));
    } */

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
                             ->paginate(100);
            } else {
                // Récupérer toutes les zones pour l'admin
                $zones = Zone::with('details')->paginate(100);
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
                $zones = Zone::with('details')->paginate(25);
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
        $zone = Zone::with('details')->findOrFail($id); // Utilise la relation 'details'
        return view('admin.zone.show', compact('zone'));
    }

   /*  public function store(ZoneFormRequest $request)
    {
        $data = $request->validated();

        // Créer la zone principale
        $zone = new Zone();
        $zone->Commune = $data['Commune'];
        $zone->PlageZone = $data['PlageZone'];
        $zone->save();

        // Calculer la plage de zones
        [$start, $end] = explode('-', $data['PlageZone']);
        $start = intval($start);
        $end = intval($end);

        // Créer les détails associés
        foreach ($data['NomZone'] as $key => $nomZone) {
            $numeroZone = $start + $key; // Génération du numéro de zone

            // Vérifier si le numéro est dans la plage
            if ($numeroZone > $end) {
                break; // Éviter de dépasser la plage spécifiée
            }

            ZoneDetail::create([
                'zone_id' => $zone->id,
                'NumeroZone' => $numeroZone, // Ajouter le numéro de zone
                'NomZone' => $nomZone,
                'CoursierName' => $data['CoursierName'][$key],
                'CoursierCode' => $data['CoursierCode'][$key],
                'CoursierPhone' => $data['CoursierPhone'][$key],
            ]);
        }

        return redirect('admin/zone')->with('message', 'Zone ajoutée avec succès.');
    } */

    public function store(ZoneFormRequest $request)
{
    $data = $request->validated();

    // Créer la zone principale
    $zone = new Zone();
    $zone->Commune = $data['Commune'];
    $zone->PlageZone = $data['PlageZone'];
    $zone->save();

    // Créer les détails associés
    foreach ($data['NomZone'] as $key => $nomZone) {
        ZoneDetail::create([
            'zone_id' => $zone->id,
            'NomZone' => $nomZone,
            'CoursierName' => $data['CoursierName'][$key],
            'CoursierCode' => $data['CoursierCode'][$key],
            'CoursierPhone' => $data['CoursierPhone'][$key],
            'NumeroZone' => $key, // Numéro de zone ajouté ici
        ]);
    }

    return redirect('admin/zone')->with('message', 'Zone ajoutée avec succès.');
}


public function edit($zone_id)
{
    $zone = Zone::with('details')->findOrFail($zone_id);
    return view('admin.zone.edit', compact('zone'));
}
public function update(ZoneFormRequest $request, $zone_id) {
    $data = $request->validated();

    // Récupérer la zone principale
    $zone = Zone::findOrFail($zone_id);
    $zone->Commune = $data['Commune'];
    $zone->PlageZone = $data['PlageZone'];
    $zone->save();  // Utilisation de save() pour persister les modifications de zone

    // Mettre à jour les détails associés
    foreach ($data['NomZone'] as $key => $nomZone) {
        // Trouver le détail de la zone en fonction du numéro de zone
        $zoneDetail = ZoneDetail::where('zone_id', $zone->id)
                                ->where('NumeroZone', $key)
                                ->first();  // Recherche le détail existant par zone_id et NumeroZone
        
        if ($zoneDetail) {
            // Si le détail existe, on le met à jour
            $zoneDetail->update([
                'NomZone' => $nomZone,
                'CoursierName' => $data['CoursierName'][$key],
                'CoursierCode' => $data['CoursierCode'][$key],
                'CoursierPhone' => $data['CoursierPhone'][$key],
                'NumeroZone' => $key,  // On garde le numéro de zone existant
            ]);
        } else {
            // Sinon, on crée un nouveau détail
            ZoneDetail::create([
                'zone_id' => $zone->id,
                'NomZone' => $nomZone,
                'CoursierName' => $data['CoursierName'][$key],
                'CoursierCode' => $data['CoursierCode'][$key],
                'CoursierPhone' => $data['CoursierPhone'][$key],
                'NumeroZone' => $key,  // Ajouter le numéro de zone
            ]);
        }
    }

    return redirect('admin/zone')->with('message', 'Zone modifiée avec succès.');
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


}
