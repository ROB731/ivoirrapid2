<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'NumeroZone', // Nouveau champ ajouté
        'NomZone',
        'CoursierName',
        'CoursierCode',
        'CoursierPhone',
    ];
    /**
     * Relation inverse avec Zone (Many-to-One).
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }
}
