<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['Commune', 'PlageZone','libelle_zone', 'code_coursier'];

    /**
     * Relation avec ZoneDetail (One-to-Many).
     */
    public function details()
    {
        return $this->hasMany(ZoneDetail::class, 'zone_id', 'id');
    }

    public function coursier()
        {
            return $this->belongsTo(Coursier::class, 'code_coursier', 'code');
        }




    //       public function details()
    // {
    //     return $this->hasMany(ZoneDetail::class, 'zone_id'); // Vérifie que 'zone_id' est le bon champ
    // }

    // public function coursier()
    // {
    //     return $this->hasMany(Coursier::class, 'zone_id'); // Vérifie aussi le champ clé
    // }

}
