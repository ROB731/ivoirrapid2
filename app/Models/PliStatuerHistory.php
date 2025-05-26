<?php

// Modèle PliStatuerHistory
namespace App\Models;

use App\Models\Pli;
use App\Models\Statuer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PliStatuerHistory extends Model
{
    use HasFactory;

    protected $fillable = ['pli_id', 'statuer_id', 'date_changement', 'raison_annulation'];
    protected $table = 'pli_statuer_history'; // Nom explicite de la table
    public function statuer()
    {
        return $this->belongsTo(Statuer::class);
    }

    public function pli()
    {
        return $this->belongsTo(Pli::class, 'pli_id');
    }

}

