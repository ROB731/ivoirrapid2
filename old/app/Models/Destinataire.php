<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'contact',
        'adresse',
        'zone',
        'autre',
    ];

    // Dans le modèle Destinataire
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

