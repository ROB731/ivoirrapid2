<?php

namespace App\Observers;

use App\Models\Pli;
use App\Models\HistoriquePli;
use Illuminate\Support\Facades\Auth;



class PliObserver
{
    public function updated(Pli $pli)
    {
        HistoriquePli::create([
            'pli_id' => $pli->id,
            'client_id' => Auth::id(),
            'action' => 'modifié',
            'ancienne_valeur' => json_encode($pli->getOriginal()),
            'nouvelle_valeur' => json_encode($pli->getAttributes()),
            'date_action' => now(),
        ]);
    }

    public function deleted(Pli $pli)
    {
        HistoriquePli::create([
            'pli_id' => $pli->id,
            'client_id' => Auth::id(),
            'action' => 'supprimé',
            'ancienne_valeur' => json_encode($pli->getOriginal()),
            'date_action' => now(),
        ]);
    }
}
