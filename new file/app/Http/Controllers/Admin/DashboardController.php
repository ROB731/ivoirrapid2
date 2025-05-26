<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coursier;
use App\Models\Destinataire;
use App\Models\Pli;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $users = User::all();
    $destinataires = Destinataire::all();
    $plis = Pli::all();
    $coursiers = Coursier::all();
    $totalUsers = User::count(); // Compte tous les utilisateurs
    $totalDestinataires = Destinataire::count(); // Compte tous les destinataires
    $totalPlis = Pli::count();  // Compte le nombre total de plis
    $totalCoursiers = Coursier::count();  // Compte le nombre total de coursiers
    return view('admin.dashboard', compact('users', 'totalUsers', 'totalDestinataires', 'destinataires', 'totalPlis', 'plis', 'totalCoursiers', 'coursiers'));
}
}
