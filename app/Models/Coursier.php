<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Attribution;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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




    // Nouvelles fonctions

        public function scopeTopCoursiersAnnuel($query)
        {
            return $query->withCount(['attributions as total_attributions' => function ($q) {
                $q->whereYear('created_at', Carbon::now()->year);
            }])->orderByDesc('total_attributions');
        }

        public function scopeTopCoursiersMensuel($query)
        {
            return $query->withCount(['attributions as total_attributions' => function ($q) {
                $q->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
            }])->orderByDesc('total_attributions');
        }

        public function scopeTopCoursiersHebdomadaire($query)
        {
            return $query->withCount(['attributions as total_attributions' => function ($q) {
                $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }])->orderByDesc('total_attributions');
        }

        public function scopeTopCoursiersJournalier($query)
        {
            return $query->withCount(['attributions as total_attributions' => function ($q) {
                $q->whereDate('created_at', Carbon::today());
            }])->orderByDesc('total_attributions');
        }



        public function attributionsRamassage()
            {
                return $this->hasMany(Attribution::class, 'coursier_ramassage_id');
            }

            public function attributionsDepot()
            {
                return $this->hasMany(Attribution::class, 'coursier_depot_id');
            }






}// Fin de fermeture
