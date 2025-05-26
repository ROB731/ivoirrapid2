<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
        */
    public function destinataires()
{
    return $this->hasMany(Destinataire::class, 'user_id');
}
     protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'abreviation',
        'Telephone',
        'Cellulaire',
        'Compte_contribuable',
        'RCCM',
        'Direction_1_Nom_et_Prenoms',
        'Direction_1_Contact',
        'Direction_2_Nom_et_Prenoms',
        'Direction_2_Contact',
        'Direction_3_Nom_et_Prenoms',
        'Direction_3_Contact',
        'Adresse',
        'Commune',
        'Quartier',
        'Rue',
        'Zone',
        'Autre',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


}
