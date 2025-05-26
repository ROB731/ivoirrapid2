<?php

i->user_cellulaire = $user->Cellulaire;
    $pli->user_autre = $user->Autre;

    // Informations sur le colis
    $pli->type = $data['type'];
    $pli->nombre_de_pieces = $data['nombre_de_pieces'];
    $pli->reference = $data['reference'];

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



    // Méthodes pour edit, update, et destroy peuvent être ajoutées ici si nécessaire
}
