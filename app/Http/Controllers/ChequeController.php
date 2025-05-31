<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cheque;
use App\Models\ChequeHistory;

class ChequeController extends Controller
{
    // Liste des chèques
    public function index()
    {
        $cheques = Cheque::latest()->paginate(10);
        return view('admin.services-cheques.index', compact('cheques'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('cheques.create');
    }

    // Enregistrer un nouveau chèque
    public function store(Request $request)
    {
        $request->validate([
            'numero_cheque' => 'required|unique:cheques',
            'montant' => 'required|numeric',
            'date_emission' => 'required|date',
            'beneficiaire' => 'required|string',
            'banque_emettrice' => 'required|string',
            'statut' => 'required|in:En attente,Encaisse,Rejeté,Annulé',
        ]);

        $cheque = Cheque::create($request->all());

        // Sauvegarder l'historique du statut
        ChequeHistory::create([
            'cheque_id' => $cheque->id,
            'ancien_statut' => null,
            'nouveau_statut' => $cheque->statut,
            'motif_changement' => 'Création du chèque'
        ]);

        return redirect()->route('cheques.index')->with('success', 'Chèque ajouté avec succès.');
    }

    // Afficher un chèque spécifique
    public function show(Cheque $cheque)
    {
        return view('cheques.show', compact('cheque'));
    }

    // Modifier un chèque existant
    public function edit(Cheque $cheque)
    {
        return view('cheques.edit', compact('cheque'));
    }

    // Mettre à jour un chèque
    public function update(Request $request, Cheque $cheque)
    {
        $ancienStatut = $cheque->statut;

        $request->validate([
            'montant' => 'numeric',
            'statut' => 'in:En attente,Encaisse,Rejeté,Annulé',
        ]);

        $cheque->update($request->all());

        // Enregistrer le changement de statut
        ChequeHistory::create([
            'cheque_id' => $cheque->id,
            'ancien_statut' => $ancienStatut,
            'nouveau_statut' => $cheque->statut,
            'motif_changement' => 'Mise à jour du chèque'
        ]);

        return redirect()->route('cheques.index')->with('success', 'Chèque mis à jour.');
    }

    // Supprimer un chèque
    public function destroy(Cheque $cheque)
    {
        $cheque->delete();
        return redirect()->route('admin.services-cheques.index')->with('success', 'Chèque supprimé.');
    }
}
