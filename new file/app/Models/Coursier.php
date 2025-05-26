<?php

namespace App\Models;

use App\Models\Attribution;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coursier extends Model
{
    use HasFactory;

    protected $table = 'coursiers';

    // Les colonnes qui peuvent être remplies via un formulaire ou une requête
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
        'zones', // Mise à jour pour gérer plusieurs zones
    ];

    /**
     * Relation : un coursier peut avoir plusieurs attributions.
     */
    public function attributions()
    {
        return $this->hasMany(Attribution::class);
    }
    public function zones()
{
    return $this->belongsToMany(Zone::class, 'coursier_zones'); // Table pivot : coursier_zones
}


    /**
     * Accesseur : convertir la chaîne de zones en tableau.
     * Exemple : "Abidjan, Yopougon" devient ['Abidjan', 'Yopougon']
     */
    public function getZonesAttribute($value)
    {
        return explode(', ', $value); // Retourne un tableau
    }

    /**
     * Mutateur : convertir le tableau de zones en chaîne avant sauvegarde.
     * Exemple : ['Abidjan', 'Yopougon'] devient "Abidjan, Yopougon"
     */
    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = implode(', ', $value); // Sauvegarde une chaîne
    }

    /**
     * Méthode statique pour récupérer les coursiers d'une zone spécifique.
     * Utilise 'like' pour vérifier si la zone est contenue dans le champ 'zones'.
     */
    public static function getCoursiersByZone($zone)
    {
        return self::where('zones', 'like', '%' . $zone . '%')->get();
    }
}
