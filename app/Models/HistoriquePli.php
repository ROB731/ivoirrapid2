<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriquePli extends Model
{
    use HasFactory;

    protected $table = 'plis_historique'; // Nom personnalisé

    protected $fillable = ['pli_id', 'client_id', 'action', 'ancienne_valeur', 'nouvelle_valeur', 'date_action'];

    public $timestamps = false; // Pas besoin des `created_at` et `updated_at`, car `date_action` gère ça

    // Relation avec le modèle Pli
    public function pli()
    {
        return $this->belongsTo(Pli::class);
    }

    // Relation avec le client (User)
    public function client()
    {
        return $this->belongsTo(User::class);
    }
}
