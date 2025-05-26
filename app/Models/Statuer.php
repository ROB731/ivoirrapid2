<?php

// Modèle Statuer
namespace App\Models;

use App\Models\PliStatuerHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuer extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $table = 'statuer'; // Nom explicite de la table

    public function pliStatuerHistories()
    {
        return $this->hasMany(PliStatuerHistory::class);
    }
    public function plis()
{
    return $this->hasMany(Pli::class, 'statuer_id');
}

}
