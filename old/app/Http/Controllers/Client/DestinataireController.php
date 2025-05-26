<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\DestinataireFormRequest;
use App\Models\Destinataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinataireController extends Controller
{
    // Afficher les destinataires en fonction du rôle de l'utilisateur
    public function index()
    {
        if (Auth::user()->role_as == '1') {
            // Admin voit tous les destinataires triés par ordre alphabétique avec pagination
            $destinataires = Destinataire::orderBy('name', 'asc')->paginate(50);
            return view('admin.destinataires.index', compact('destinataires'));
        } else {
            // Utilisateur basique voit seulement ses destinataires triés par ordre alphabétique avec pagination
            $destinataires = Auth::user()->destinataires()->orderBy('name', 'asc')->paginate(50);
            return view('client.destinataires.index', compact('destinataires'));
        }
    }



    // Afficher le formulaire de création uniquement pour les utilisateurs basiques
    public function create()
    {
        if (Auth::user()->role_as == '1') {
            abort(403, 'Accès refusé'); // Empêcher l'admin d'accéder à la création
        }
        return view('client.destinataires.create');
    }

    // Stocker le destinataire uniquement pour les utilisateurs basiques
    public function store(DestinataireFormRequest $request)
    {
        if (Auth::user()->role_as == '1') {
            abort(403, 'Accès refusé'); // Empêcher l'admin de créer un destinataire
        }

        $data = $request->validated();
        $destinataire = new Destinataire;
        $destinataire->name = $data['name'];
        $destinataire->adresse = $data['adresse'];
        $destinataire->telephone = $data['telephone'];
        $destinataire->contact = $data['contact'];
        $destinataire->email = $data['email'];
        $destinataire->zone = $data['zone'];
        $destinataire->autre = $data['autre'];
        $destinataire->user_id = auth()->id(); // Associer le destinataire à l'utilisateur connecté
        $destinataire->save();

        return redirect()->route('client.destinataires.index')->with('success', 'Destinataire créé avec succès.');
    }

    public function edit($destinataire_id){
        $destinataire = Destinataire::find($destinataire_id);
        return view('client.destinataires.edit', compact('destinataire'));
    }

    public function update($destinataire_id, DestinataireFormRequest $request){
        $data = $request->validated();
        $destinataire = Destinataire::find($destinataire_id);
        $destinataire->name = $data['name'];
        $destinataire->adresse = $data['adresse'];
        $destinataire->telephone = $data['telephone'];
        $destinataire->contact = $data['contact'];
        $destinataire->email = $data['email'];
        $destinataire->zone = $data['zone'];
        $destinataire->autre = $data['autre'];
        $destinataire->user_id = auth()->id(); // Associer le destinataire à l'utilisateur connecté
        $destinataire->update();

        return redirect()->route('client.destinataires.index')->with('success', 'Destinataire modifié avec succès.');

    }

    public function destroy($destinataire_id){
        $destinataire = Destinataire::find($destinataire_id);
        if($destinataire){
            if(Auth::user()->role_as == '1'){
                abort(403, 'Accès refusé'); // Empêcher l'admin de supprimer un destinataire
            }
            $destinataire->delete();
            return redirect()->route('client.destinataires.index')->with('success', 'Destinataire supprimé avec succès.'); // Retour à la liste des destinataires avec un message de succès
        }
        else{
            return redirect()->route('client.destinataires.index')->with('error', 'Destinataire introuvable.'); // Retour à la liste des destinataires avec un message d'erreur si le destinataire n'existe pas
        }
        
    }
}
