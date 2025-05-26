public function store(PliFormRequest $request)
{
    // Validation des données
    $data = $request->validated();

    // Créer une nouvelle instance de Pli
    $pli = new Pli();
    $pli->destinataire_id = $data['destinataire_id'];
    $pli->user_id = Auth::id(); // Associer le pli à l'utilisateur connecté

    // Récupérer les informations du destinataire
    $destinataire = Destinataire::findOrFail($data['destinataire_id']);
    $pli->destinataire_name = $destinataire->name;
    $pli->destinataire_adresse = $destinataire->adresse;
    $pli->destinataire_telephone = $destinataire->telephone;
    $pli->destinataire_email = $destinataire->email;
    $pli->destinataire_zone = $destinataire->zone;
    $pli->destinataire_contact = $destinataire->contact;
    $pli->destinataire_autre = $destinataire->autre;

    // Récupérer les informations de l'expéditeur
    $user = Auth::user();
    $pli->user_name = $user->name;
    $pli->user_adresse = $user->Adresse;
    $pli->user_telephone = $user->Telephone;
    $pli->user_email = $user->email;
    $pli->user_zone = $user->Zone;
    $pli->user_cellulaire = $user->Cellulaire;
    $pli->user_autre = $user->Autre;

    // Informations sur le colis
    $pli->type = $data['type'];
    $pli->nombre_de_pieces = $data['nombre_de_pieces'];

    // Si 'reference' est un tableau, le convertir en chaîne
    $pli->reference = implode(',', $data['reference']); // Concatène toutes les références

    // Générer le code du pli
    $year = Carbon::now()->format('y'); // Année en deux chiffres
    $month = Carbon::now()->format('m'); // Mois en deux chiffres

    // Utiliser le nom de l'utilisateur pour le code
    $userName = strtolower(str_replace(' ', '_', $user->name)); // Remplacer les espaces par des underscores et mettre en minuscules

    // Trouver le dernier pli créé par cet utilisateur pour la génération du prochain code
    $lastPli = Pli::where('user_id', Auth::id())
        ->where('code', 'like', "$userName-$year-$month%")
        ->orderBy('created_at', 'desc')
        ->first();

    // Calculer le prochain numéro d'incrément
    $nextNumber = $lastPli ? intval(substr($lastPli->code, -6)) + 1 : 1;
    $nextNumberPadded = str_pad($nextNumber, 6, '0', STR_PAD_LEFT); // Ajouter des zéros pour avoir 6 chiffres

    // Générer le code final
    $pli->code = "$userName-$year-$month-$nextNumberPadded";

    // Enregistrer le pli
    $pli->save();

    return redirect()->route('client.plis.index')->with('success', 'Pli créé avec succès.');
}
