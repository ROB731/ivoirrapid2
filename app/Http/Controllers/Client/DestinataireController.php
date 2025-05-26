<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Pli;
use App\Models\User;
use App\Models\Destinataire;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Client\DestinataireFormRequest;

class DestinataireController extends Controller
{
    //Afficher les destinataires en fonction du rôle de l'utilisateur

    public function index()
    {
        if (Auth::user()->role_as == '1' ) {
            // Admin voit tous les destinataires triés par ordre alphabétique avec pagination
            $destinataires = Destinataire::orderBy('name', 'asc')->paginate(1000);
            return view('admin.destinataires.index', compact('destinataires'));
        } else {
            // Utilisateur basique voit seulement ses destinataires triés par ordre alphabétique avec pagination
            $destinataires = Auth::user()->destinataires()->orderBy('name', 'asc')->paginate(500);
            return view('client.destinataires.index', compact('destinataires'));
        }

            //     // Récupérer les 5 derniers plis créés
            // $derniersPlis = Pli::orderBy('created_at', 'desc')->limit(5)->get();

            // // Nombre de plis créés par période
            // $aujourdhui = Pli::whereDate('created_at', Carbon::today())->count();
            // $hier = Pli::whereDate('created_at', Carbon::yesterday())->count();
            // $semaineDerniere = Pli::whereBetween('created_at', [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->startOfWeek()])->count();
            // $moisDernier = Pli::whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()])->count();

    }



        public function getUsers(Request $request)
        {
            $query = $request->input('query');

            $users = User::where('name', 'LIKE', "%{$query}%")
                        ->orderBy('name', 'asc')
                        ->limit(10)
                        ->get(['id', 'name']);

            return response()->json($users);
        }


    //  public function getDestinataires(Request $request)
    //     {
    //         $query = $request->input('query');

    //         $destinataires = Destinataire::where('name', 'LIKE', "%{$query}%")
    //                     ->orderBy('name', 'asc')
    //                     ->limit(10)
    //                     ->get(['id', 'name']);

    //         return response()->json($destinataires);
    //     }



        public function getDestinataires(Request $request)
            {
                $query = $request->input('query');

                $destinataires = Destinataire::where('name', 'LIKE', "%{$query}%")
                            ->orderBy('name', 'asc')
                            ->limit(10)
                            ->get(['id', 'name', 'adresse', 'telephone', 'email', 'zone', 'contact', 'autre']);

                return response()->json($destinataires);
            }








    // public function index()
    //         {
    //             // Tous les destinataires seront visibles par tous les utilisateurs
    //             $destinataires = Destinataire::All('name', 'asc')->paginate(50);

    //             return view('client.destinataires.index', compact('destinataires'));
    //         }




    // Afficher le formulaire de création uniquement pour les utilisateurs basiques
    // public function create()
    // {
    //     if (Auth::user()->role_as == '1') {  // Donc on est obligé de se connecter sur leur page pour faire des saisir
    //         abort(403, 'Accès refusé'); // Empêcher l'admin d'accéder à la création
    //     }
    //     return view('client.destinataires.create');
    // }

    public function create()
    {
        if (Auth::user()) {  // Donc on est obligé de se connecter sur leur page pour faire des saisir
            return view('client.destinataires.create');

        }

        abort(403, 'Accès refusé'); // Empêcher l'admin d'accéder à la création
    }

    // Stocker le destinataire uniquement pour les utilisateurs basiques
    public function store(DestinataireFormRequest $request)
    {
        if (Auth::user())
    //    if (Auth::user() )

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


                {
                    abort(403, 'Accès refusé'); // Empêcher l'admin de créer un destinataire
                }



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
                if(Auth::user()){ // Si tous les utilisateurs sont connecter y compris les admin
                    // abort(403, 'Accès refusé'); // Empêcher l'admin de supprimer un destinataire
                    $destinataire->delete();
                    return redirect()->route('client.destinataires.index')->with('success', 'Destinataire supprimé avec succès.'); // Retour à la liste des destinataires avec un message de succès

                }
                else
                    {
                        abort(403, 'Accès refusé'); // Empêcher l'admin de supprimer un destinataire
                    }

         }
        else{
            return redirect()->route('client.destinataires.index')->with('error', 'Destinataire introuvable.'); // Retour à la liste des destinataires avec un message d'erreur si le destinataire n'existe pas
        }

    }
}
