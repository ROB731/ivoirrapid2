<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class ZoneDetail extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'zone_id',
    //     'NumeroZone', // Nouveau champ ajouté
    //     'NomZone',
    //     'libelle_detail_zones'
    //     // 'CoursierName',
    //     // 'CoursierCode',
    //     // 'CoursierPhone',
    // ];


        protected $fillable = [
             'zone_id',
            'NomZone',
            'NumeroZone',
            'libelle_detail_zones',
        ];



    /**
     * Relation inverse avec Zone (Many-to-One).
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }


   public function entreprises() {
        return $this->hasMany(User::class);
    }

    public function destinataire(){
        return $this->hasmany(Destinataire::class);
    }
}
