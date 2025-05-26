<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coursier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CoursierFormRequest;

class CoursierController extends Controller
{
    public function index()
    {
        return view('admin.coursiers.index');
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
        
        $coursier->save();
    }
}
