<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['Commune', 'PlageZone'];

    /**
     * Relation avec ZoneDetail (One-to-Many).
     */
    public function details()
    {
        return $this->hasMany(ZoneDetail::class, 'zone_id', 'id');
    }
}
