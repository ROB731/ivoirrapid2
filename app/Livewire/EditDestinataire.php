<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Destinataire;


class EditDestinataire extends Component
{
    public $destinataires;
    public $selectedDest;
    public $zone, $adresse;

    public function mount()
    {
        $this->destinataires = Destinataire::latest()->get();
    }

    public function selectDest($id)
    {
        $this->selectedDest = Destinataire::find($id);
        if ($this->selectedDest) {
            $this->zone = $this->selectedDest->zone;
            $this->adresse = $this->selectedDest->adresse;
        }
    }

    public function updateDestinataire()
    {
        if ($this->selectedDest) {
            $this->selectedDest->update([
                'zone' => $this->zone,
                'adresse' => $this->adresse,
            ]);
            session()->flash('success', 'Modification réussie ✅');
        }
    }

    public function render()
    {
        return view('livewire.edit-destinataire');
    }
}
