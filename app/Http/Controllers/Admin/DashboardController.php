<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pli;
use App\Models\User;
use App\Models\Facture;
use App\Models\Coursier;
use App\Models\Destinataire;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{




public function index()
{
    // Récupération des statistiques générales
    $users = User::all();
    $destinataires = Destinataire::all();
    $plis = Pli::all();
    $coursiers = Coursier::all();

    $totalUsers = User::count();
    $totalDestinataires = Destinataire::count();
    $totalPlis = Pli::count();
    $totalCoursiers = Coursier::count();

    // Ajout des statistiques pour chaque statut des plis
    $plisEnAttenteJour = Pli::countPlisEnAttenteJour();
    $plisEnAttenteSemaine = Pli::countPlisEnAttenteSemaine();
    $plisEnAttenteMois = Pli::countPlisEnAttenteMois();

    $plisRamassesJour = Pli::countPlisRamassesJour();
    $plisRamassesSemaine = Pli::countPlisRamassesSemaine();
    $plisRamassesMois = Pli::countPlisRamassesMois();

    $plisDeposesJour = Pli::countPlisDeposesJour();
    $plisDeposesSemaine = Pli::countPlisDeposesSemaine();
    $plisDeposesMois = Pli::countPlisDeposesMois();

    $plisAnnulesJour = Pli::countPlisAnnulesJour();
    $plisAnnulesSemaine = Pli::countPlisAnnulesSemaine();
    $plisAnnulesMois = Pli::countPlisAnnulesMois();

    $plisRetournesJour = Pli::countPlisRetournesJour();
    $plisRetournesSemaine = Pli::countPlisRetournesSemaine();
    $plisRetournesMois = Pli::countPlisRetournesMois();

    $plisNonTraites = Pli::countPlisNonTraites();

    // Ajout des statistiques des factures
    // Ajout des statistiques des factures
    $facturesJour = Facture::countFacturesJour();
    $facturesSemaine = Facture::countFacturesSemaine();
    $facturesMois = Facture::countFacturesMois();

      // Ajout des statistiques des plis créés
      $plisJour = Pli::countPlisJour();
      $plisSemaine = Pli::countPlisSemaine();
      $plisWeekend = Pli::countPlisWeekend();

      // Récupération des statistiques des jours et semaines de fort trafic
    $joursFortTrafic = Pli::getJoursFortTrafic();
    $semainesFortTrafic = Pli::getSemainesFortTrafic();

    // Récupération des statistiques des mois à fort trafic et comparaison d’activité
    $moisFortTrafic = Pli::getMoisFortTrafic();
    $comparaisonActivite = Pli::comparerActiviteMois();

     // Récupération des statistiques des 12 mois et comparaison
     $activite12Mois = Pli::getActivite12Mois();
     $comparaisonActivite12Mois = Pli::comparerActivite12Mois();


     // Récupération des statistiques des 12 derniers mois pour les plis
    $comparaisonActivite12Mois = Pli::comparerActivite12Mois();

      // Récupération des statistiques des 12 derniers mois
    // $comparaisonPlis12Mois = Pli::getComparaison12Mois();

     // Récupération des statistiques des 12 derniers mois pour les états des plis
    //  $evolutionEtatsPlis = Pli::getEvolutionEtatsPlis();


    return view('admin.dashboard', compact(
        'users', 'totalUsers', 'totalDestinataires', 'destinataires',
        'totalPlis', 'plis', 'totalCoursiers', 'coursiers',
        'plisEnAttenteJour', 'plisEnAttenteSemaine', 'plisEnAttenteMois',
        'plisRamassesJour', 'plisRamassesSemaine', 'plisRamassesMois',
        'plisDeposesJour', 'plisDeposesSemaine', 'plisDeposesMois',
        'plisAnnulesJour', 'plisAnnulesSemaine', 'plisAnnulesMois',
        'plisRetournesJour', 'plisNonTraites','facturesJour', 'facturesSemaine', 'facturesMois',
        'plisJour', 'plisSemaine', 'plisWeekend','joursFortTrafic', 'semainesFortTrafic',
        'moisFortTrafic', 'comparaisonActivite','activite12Mois','comparaisonActivite12Mois'

    ));
}


// public function index()
// {
//     $plisAnnulesJour = Pli::countPlisAnnulesJour();
//     $plisAnnulesSemaine = Pli::countPlisAnnulesSemaine();
//     $plisAnnulesMois = Pli::countPlisAnnulesMois();

//     return view('dashboard.index', compact('plisAnnulesJour', 'plisAnnulesSemaine', 'plisAnnulesMois'));
// }



} // Fin principal
