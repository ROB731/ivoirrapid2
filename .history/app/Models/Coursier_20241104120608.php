<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coursier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenoms',
        'telephone',
        'email',
        'code',
        'numero_de_permis',
        'date_de_validite_du_permis',
        'categorie_du_permis',
        'numero_de_cni',
        'date_de_validite_de_la_cni',
        'numero_de_la_cmu',
        'date_de_validite_de_la_cmu',
        'date_de_naissance',
        'groupe_sanguin',
        'date_de_debut_du_contrat',
        'date_de_fin_du_contrat',
        'adresse',
        'contact_urgence',
        'affiliation_urgence',
}
