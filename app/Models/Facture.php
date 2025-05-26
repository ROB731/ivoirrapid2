<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facture extends Model
{
    use HasFactory;

             protected $table = 'factures';

            public static function getFacturesJour()
        {
            return self::whereDate('created_at', Carbon::today())->get();
        }

        public static function getFacturesSemaine()
        {
            return self::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        }

        public static function getFacturesMois()
        {
            return self::whereMonth('created_at', Carbon::now()->month)->get();
        }


        public static function countFacturesJour() // Compte les facture par période
            {
                return self::whereDate('created_at', Carbon::today())->count();
            }

            public static function countFacturesSemaine()
            {
                return self::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
            }

            public static function countFacturesMois()
            {
                return self::whereMonth('created_at', Carbon::now()->month)->count();
            }





}// Fin principal






