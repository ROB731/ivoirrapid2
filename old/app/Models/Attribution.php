<?php

namespace App\Models;

use App\Models\Coursier;
use App\Models\Pli;
use Illuminate\Database\Eloquent\Model;

class Attribution extends Model
{
    protected $fillable = ['pli_id', 'coursier_ramassage_id', 'coursier_depot_id', 'type','date_attribution_ramassage',
        'date_attribution_depot',];
        protected $dates = [
            'date_attribution_ramassage',
            'date_attribution_depot',
            'created_at',
            'updated_at'
        ];
        
    // Relation avec le coursier de ramassage
    public function coursierRamassage()
    {
        return $this->belongsTo(Coursier::class, 'coursier_ramassage_id');
    }

    // Relation avec le coursier de dépôt
    public function coursierDepot()
    {
        return $this->belongsTo(Coursier::class, 'coursier_depot_id');
    }

    // Relation avec le pli
    public function pli()
    {
        return $this->belongsTo(Pli::class);
    }

    
}
