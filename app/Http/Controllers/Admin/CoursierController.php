<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coursier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoursierFormRequest;

class CoursierController extends Controller
{
  public function index()
    {
        // Trier les coursiers par ordre alphabétique en fonction du champ "nom"
        $coursiers = Coursier::orderBy('nom', 'asc')->paginate(50); // Paginer les coursiers par 50
        return view('admin.coursiers.index', compact('coursiers'));
    }

    public function create()
    {
        return view('admin.coursiers.create');
    }

    public function store(CoursierFormRequest $request)
    {
        $data = $request->validated();

        $coursier = new Coursier();

        $coursier->nom = $data['nom'];
        $coursier->prenoms = $data['prenoms'];
        $coursier->telephone = $data['telephone'];
        $coursier->email = $data['email'];
        $coursier->code = $data['code'];
        $coursier->numero_de_permis = $data['numero_de_permis'];
        $coursier->date_de_validite_du_permis = $data['date_de_validite_du_permis'];
        $coursier->categorie_du_permis = $data['categorie_du_permis'];
        $coursier->numero_de_cni = $data['numero_de_cni'];
        $coursier->date_de_validite_de_la_cni = $data['date_de_validite_de_la_cni'];
        $coursier->numero_de_la_cmu = $data['numero_de_la_cmu'];
        $coursier->date_de_validite_de_la_cmu = $data['date_de_validite_de_la_cmu'];
        $coursier->date_de_naissance = $data['date_de_naissance'];
        $coursier->groupe_sanguin = $data['groupe_sanguin'];
        $coursier->date_de_debut_du_contrat = $data['date_de_debut_du_contrat'];
        $coursier->date_de_fin_du_contrat = $data['date_de_fin_du_contrat'];
        $coursier->adresse = $data['adresse'];
        $coursier->contact_urgence = $data['contact_urgence'];
        $coursier->affiliation_urgence = $data['affiliation_urgence'];

        // Conversion des zones en tableau avant de les sauvegarder
        $coursier->zones = $data['zones']; // Les zones sont passées comme tableau depuis le formulaire

        $coursier->save();

        return redirect()->route('admin.coursiers.index')->with('success', 'Coursier créé avec succès');
    }

    public function edit($coursier_id)
{
    // Récupérer le coursier en fonction de son ID
    $coursier = Coursier::findOrFail($coursier_id);

    // Retourner les données du coursier à la vue
    return view('admin.coursiers.edit', compact('coursier'));
}


public function update($coursier_id, CoursierFormRequest $request)
{
    // Validation des données depuis le formulaire
    $data = $request->validated();

    // Récupération du coursier
    $coursier = Coursier::findOrFail($coursier_id);

    // Mise à jour des champs principaux
    $coursier->nom = $data['nom'];
    $coursier->prenoms = $data['prenoms'];
    $coursier->telephone = $data['telephone'];
    $coursier->email = $data['email'];
    $coursier->code = $data['code'];
    $coursier->numero_de_permis = $data['numero_de_permis'];
    $coursier->date_de_validite_du_permis = $data['date_de_validite_du_permis'];
    $coursier->categorie_du_permis = $data['categorie_du_permis'];
    $coursier->numero_de_cni = $data['numero_de_cni'];
    $coursier->date_de_validite_de_la_cni = $data['date_de_validite_de_la_cni'];
    $coursier->numero_de_la_cmu = $data['numero_de_la_cmu'];
    $coursier->date_de_validite_de_la_cmu = $data['date_de_validite_de_la_cmu'];
    $coursier->date_de_naissance = $data['date_de_naissance'];
    $coursier->groupe_sanguin = $data['groupe_sanguin'];
    $coursier->date_de_debut_du_contrat = $data['date_de_debut_du_contrat'];
    $coursier->date_de_fin_du_contrat = $data['date_de_fin_du_contrat'];
    $coursier->adresse = $data['adresse'];
    $coursier->contact_urgence = $data['contact_urgence'];
    $coursier->affiliation_urgence = $data['affiliation_urgence'];

    // Mise à jour des zones (le champ zones est attendu comme un tableau)
    if (isset($data['zones']) && is_array($data['zones'])) {
        $coursier->zones = $data['zones']; // Conversion gérée automatiquement par le mutateur
    }

    // Sauvegarde des données
    $coursier->save();

    // Redirection avec message de succès
    return redirect()->route('admin.coursiers.index')->with('success', 'Coursier modifié avec succès');
}


    public function destroy($coursier_id)
    {
        $coursier = Coursier::find($coursier_id);
        if ($coursier) {
            $coursier->delete();
            return redirect()->route('admin.coursiers.index')->with('success', 'Coursier supprimé avec succès');
        } else {
            return redirect()->route('admin.coursiers.index')->with('error', 'Coursier introuvable');
        }
    }

    // Nouvelle -----

public function top10Coursiers($periode)
{
    $topCoursiers = Coursier::withCount(['attributions as total_attributions' => function ($query) use ($periode) {
        switch ($periode) {
            case 'jour':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'semaine':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'mois':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'annee':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }
    }])->orderByDesc('total_attributions')->take(10)->get();

    return $topCoursiers; // Retourne les données sans passer par une vue
}




} // Fin de la fermeture general
