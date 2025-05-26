<?php

namespace App\Models;

use App\Models\Attribution;
use App\Models\Coursier;
use App\Models\Destinataire;
use App\Models\PliStatuerHistory;
use App\Models\Statuer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pli extends Model
{
    use HasFactory;

    // Indiquez la table si le nom du modèle ne suit pas la convention de nommage
    protected $table = 'plies';

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'destinataire_id',
        'user_id',
        'destinataire_name',
        'destinataire_adresse',
        'destinataire_telephone',
        'destinataire_email',
        'destinataire_zone',
        'destinataire_contact',
        'destinataire_autre',
        'user_name',
        'user_adresse',
        'user_telephone',
        'user_email',
        'user_zone',
        'user_cellulaire',
        'user_autre',
        'type',
        'nombre_de_pieces',
        'reference',
        'date_attribution_ramassage',
        'date_attribution_depot',
    ];

    

    public function destinataire()
{
    return $this->belongsTo(Destinataire::class, 'destinataire_id');
}

    
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


    // Définir la relation avec le modèle Attribution
    public function attributions()
    {
        return $this->hasMany(Attribution::class);
    }
     // Relation avec l'historique des statuts
     public function statuerHistory()
     {
         return $this->hasMany(PliStatuerHistory::class);
     }
 
     // Méthode pour obtenir le statut actuel (le plus récent)
     public function currentStatuer()
     {
         return $this->statuerHistory()->latest()->first();  // Retourne le dernier statut
     }
     public function statuer()
{
    return $this->belongsTo(Statuer::class, 'statuer_id');
}
public function statuerHistories()
{
    return $this->hasMany(PliStatuerHistory::class, 'pli_id');
}
public function coursier()
{
    return $this->belongsTo(Coursier::class, 'coursier_id'); // Assurez-vous que 'coursier_id' existe dans la table des plis
}


 
}
