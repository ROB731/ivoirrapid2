<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Destinataire;

 class DestinataireManager extends Component
{
    public $destinataires;
    public $editedDestId;
    public $zone, $adresse, $name;

    public function mount()
    {
        $this->destinataires = Destinataire::latest()->get();
    }

    public function editDest($id)
    {
        $this->editedDestId = $id;
        $dest = Destinataire::find($id);
        if ($dest) {
            $this->zone = $dest->zone;
            $this->adresse = $dest->adresse;
            $this->name = $dest->name;
        }
    }

    public function updateDest()
    {
        if ($this->editedDestId) {
            $dest = Destinataire::find($this->editedDestId);
            if ($dest) {
                $dest->update([
                    'zone' => $this->zone,
                    'adresse' => $this->adresse,
                    'name' => $this->name,
                ]);
                session()->flash('success', 'Modifié avec succès ✅');
                $this->reset(['editedDestId', 'zone', 'adresse', 'name']);
                $this->destinataires = Destinataire::latest()->get();
            }
        }
    }

    public function addDest()
    {
        Destinataire::create([
            'name' => $this->name,
            'zone' => $this->zone,
            'adresse' => $this->adresse,
        ]);
        session()->flash('success', 'Destinataire ajouté ✅');
        $this->reset(['name', 'zone', 'adresse']);
        $this->destinataires = Destinataire::latest()->get();
    }

    public function render()
    {
        return view('livewire.destinataire-manager');
    }
}
