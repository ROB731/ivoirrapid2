<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Statuer;
use App\Models\Coursier;
use App\Models\Attribution;
use App\Models\Destinataire;
use App\Models\PliStatuerHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Pli extends Model
{
    use HasFactory;

    // Ajout du 01-05-2025------------------------------------------------------------------------------------------------
    // use SoftDeletes; // 🔥 Activer les suppressions temporaires
    protected $dates = ['deleted_at']; // 📌 Laravel va utiliser ce champ pour les suppressions
    // Fin d'ajout 01-05-2025 --------------------------------------------------------------------------------



    // Indiquez la table si le nom du modèle ne suit pas la convention de nommage
    protected $table = 'plies';


    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'destinataire_id',
        'user_id',
        'destinataire_name',
        'destinataire_adresse',
        'destinataire_telephone',
        'destinataire_email',
        'destinataire_zone',
        'destinataire_contact',
        'destinataire_autre',
        'user_name',
        'user_adresse',
        'user_telephone',
        'user_email',
        'user_zone',
        'user_cellulaire',
        'user_autre',
        'type',
        'nombre_de_pieces',
        'reference',
        'date_attribution_ramassage',
        'date_attribution_depot',
    ];






    public function destinataire()
{
    return $this->belongsTo(Destinataire::class, 'destinataire_id');
}



        public function attributions()
            {
                return $this->hasMany(Attribution::class, 'pli_id');
            }




public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}



public function pliStatuerHistory() {
    return $this->hasMany(PliStatuerHistory::class); //  Corrige la relation
}


     public function statuerHistory()
     {
         return $this->hasMany(PliStatuerHistory::class);
     }

     // Méthode pour obtenir le statut actuel (le plus récent)
     public function currentStatuer()
     {
         return $this->statuerHistory()->latest()->first();  // Retourne le dernier statut
     }
     public function statuer()
{
    return $this->belongsTo(Statuer::class, 'statuer_id');
}
public function statuerHistories()
{
    return $this->hasMany(PliStatuerHistory::class, 'pli_id');
}
public function coursier()
{
    return $this->belongsTo(Coursier::class, 'coursier_id'); // Assurez-vous que 'coursier_id' existe dans la table des plis
}

//Nouvelle fonctionnalité ou fonction pour les plis annulés ------------------------------------


    public static function getPlisAnnules()
{
    return self::whereHas('statuerHistories', function ($query) {
        $query->where('statuer_id', 4); // Filtrer uniquement les statuts "Annulé"
    })->get();
}


public static function countPlisAnnulesJour()
{
    return self::whereHas('statuerHistories', function ($query) {
        $query->where('statuer_id', 4)
            ->whereDate('date_changement', Carbon::today()); // Statut annulé aujourd'hui
    })->count();
}

public static function countPlisAnnulesSemaine()
{
    return self::whereHas('statuerHistories', function ($query) {
        $query->where('statuer_id', 4)
            ->whereBetween('date_changement', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    })->count();
}

public static function countPlisAnnulesMois()
{
    return self::whereHas('statuerHistories', function ($query) {
        $query->where('statuer_id', 4)
            ->whereMonth('date_changement', Carbon::now()->month);
    })->count();
}

public function generateIdAnnulation()
{
    return sprintf("RT N: %07d", $this->statuerHistories()->where('statuer_id', 4)->count());
}


        // En RTetourné
        public static function getPlisRetournes()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 5); // Filtrer uniquement les statuts "Retourné"
            })->get();
        } //


        public static function countPlisRetournesJour()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 5)
                    ->whereDate('date_changement', Carbon::today()); // Statut retourné aujourd'hui
            })->count();
        }

        public static function countPlisRetournesSemaine()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 5)
                    ->whereBetween('date_changement', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })->count();
        }

        public static function countPlisRetournesMois()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 5)
                    ->whereMonth('date_changement', Carbon::now()->month);
            })->count();
        }


        public function generateIdRetourne()
            {
                return sprintf("RT N: %07d", $this->statuerHistories()->where('statuer_id', 5)->count());
            } // Retourné


        //Pli en attente

        public static function getPlisEnAttente()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 1); // Filtrer uniquement les statuts "En attente"
            })->get();
        }


        public static function countPlisEnAttenteJour()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 1)
                    ->whereDate('date_changement', Carbon::today()); // Statut en attente aujourd'hui
            })->count();
        }

        public static function countPlisEnAttenteSemaine()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 1)
                    ->whereBetween('date_changement', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })->count();
        }

        public static function countPlisEnAttenteMois()
        {
            return self::whereHas('statuerHistories', function ($query) {
                $query->where('statuer_id', 1)
                    ->whereMonth('date_changement', Carbon::now()->month);
            })->count();
            }


            public function generateIdEnAttente()
            {
                return sprintf("EN N: %07d", $this->statuerHistories()->where('statuer_id', 1)->count());
            }
        //Ramassé  en bas

                    public static function getPlisRamasses()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 2); // Filtrer uniquement les statuts "Ramassé"
                })->get();
            }


            public static function countPlisRamassesJour()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 2)
                        ->whereDate('date_changement', Carbon::today()); // Statut ramassé aujourd'hui
                })->count();
            }

            public static function countPlisRamassesSemaine()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 2)
                        ->whereBetween('date_changement', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                })->count();
            }

            public static function countPlisRamassesMois()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 2)
                        ->whereMonth('date_changement', Carbon::now()->month);
                })->count();
            }


            public function generateIdRamasse()
                {
                    return sprintf("RM N: %07d", $this->statuerHistories()->where('statuer_id', 2)->count());
                }


            // Facture deposé

            public static function getPlisDeposes()
                {
                    return self::whereHas('statuerHistories', function ($query) {
                        $query->where('statuer_id', 3); // Filtrer uniquement les statuts "Déposé"
                    })->get();
                }

            public static function countPlisDeposesJour()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 3)
                        ->whereDate('date_changement', Carbon::today()); // Statut déposé aujourd'hui
                })->count();
            }

            public static function countPlisDeposesSemaine()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 3)
                        ->whereBetween('date_changement', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                })->count();
            }

            public static function countPlisDeposesMois()
            {
                return self::whereHas('statuerHistories', function ($query) {
                    $query->where('statuer_id', 3)
                        ->whereMonth('date_changement', Carbon::now()->month);
                })->count();
            }

            public function generateIdDepose()
                {
                    return sprintf("DP N: %07d", $this->statuerHistories()->where('statuer_id', 3)->count());
                }

                // Non traité -----------------------

                public static function getPlisNonTraites() // Non traités
                {
                    return self::doesntHave('statuerHistories')->get(); // Plis sans historique de statut
                }

                public static function countPlisNonTraites()
                {
                    return self::doesntHave('statuerHistories')->count(); // Nombre de plis sans historique
                }

                //-----------------------

                public static function countPlisJour()
                {
                    return self::whereDate('created_at', Carbon::today())->count(); // Plis créés aujourd'hui
                }

                public static function countPlisSemaine()
                {
                    return self::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(); // Plis créés cette semaine
                }

                public static function countPlisWeekend()
                {
                    return self::whereIn('created_at', [
                        Carbon::now()->previous(Carbon::SATURDAY)->toDateString(),
                        Carbon::now()->previous(Carbon::SUNDAY)->toDateString()
                    ])->count(); // Plis créés le weekend
                }

                // Fonction pli statis

            public static function getJoursFortTrafic()
            {
                return self::selectRaw('DATE(created_at) as jour, COUNT(*) as total')
                    ->groupBy('jour')
                    ->orderByDesc('total')
                    ->limit(7) // Afficher les 7 jours les plus chargés
                    ->get();
            }

            public static function getSemainesFortTrafic()
            {
                return self::selectRaw('YEARWEEK(created_at) as semaine, COUNT(*) as total')
                    ->groupBy('semaine')
                    ->orderByDesc('total')
                    ->limit(4) // Afficher les 4 semaines les plus chargées
                    ->get();
            }

            public static function getMoisFortTrafic()
                {
                    return self::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total')
                        ->groupBy('annee', 'mois')
                        ->orderByDesc('total')
                        ->limit(6) // Afficher les 6 derniers mois les plus actifs
                        ->get();
                }

                public static function comparerActiviteMois()
                {
                    $moisActuel = self::whereMonth('created_at', Carbon::now()->month)->count();
                    $moisPrecedent = self::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();

                    return [
                        'mois_actuel' => $moisActuel,
                        'mois_precedent' => $moisPrecedent,
                        'variation' => $moisActuel - $moisPrecedent // Différence de trafic
                    ];
                }


        public static function getActivite12Mois()
            {
                return self::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois, COUNT(*) as total')
                    ->groupBy('annee', 'mois')
                    ->orderByDesc('annee')
                    ->orderByDesc('mois')
                    ->limit(12) // Récupérer les 12 derniers mois
                    ->get();
            }



            public static function comparerActivite12Mois()
            {
                $activite = self::getActivite12Mois();
                $comparaison = [];

                foreach ($activite as $index => $mois)
                {
                    $moisActuel = $mois->total;
                    $moisPrecedent = $index < count($activite) - 1 ? $activite[$index + 1]->total : 0;
                    $variation = $moisActuel - $moisPrecedent;

                    $comparaison[] = [
                        'annee' => $mois->annee,
                        'mois' => $mois->mois,
                        'total' => $moisActuel,
                        'variation' => $variation
                    ];
                }

                return $comparaison;
            }



} // Fermeture chrochet principal
