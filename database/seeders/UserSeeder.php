<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'id' => 3,
                'name' => 'webmaster',
                'email' => 'webmaster2@ivoirrapid.ci',
                'password' => Hash::make('webmaster2024'), // Vous pouvez également insérer le hash directement
                'role_as' => 2,
                'abreviation' => 'WMIR',
                'Telephone' => '0757778710',
                'Cellulaire' => '0554841210',
                'Compte_contribuable' => '0757779-Y',
                'RCCM' => 'CI-ABJ-2024-L-5678',
                'Direction_1_Nom_et_Prenoms' => 'Fofana Idryssa',
                'Direction_1_Contact' => '2505789474',
                'Direction_2_Nom_et_Prenoms' => 'Guédé Marye',
                'Direction_2_Contact' => '2503456444',
                'Direction_3_Nom_et_Prenoms' => 'Sangaré Aly',
                'Direction_3_Contact' => '2507712444',
                'Adresse' => 'Rue des Énergies',
                'Commune' => 'Angré',
                'Quartier' => '7ème Tranche',
                'Rue' => 'Rue 18',
                'Zone' => 'Zone résidentielle',
                'Autre' => 'Non loin du carrefour',
          ],
            // [
            //     'id' => 2,
            //     'name' => 'Ivoirrapid',
            //     'email' => 'directionivoirrapid@gmail.com',
            //     'password' => Hash::make('ivoirrapid2024'),
            //     'created_at' => '2024-11-26 08:01:46',
            //     'updated_at' => '2024-11-26 08:01:46',
            //     'role_as' => 0,
            //     'abreviation' => 'IR',
            //     'Telephone' => '0768700500',
            //     'Cellulaire' => '2721374346',
            //     'Compte_contribuable' => '2218066-B',
            //     'RCCM' => 'CI-ABJ-03-2022-B13-01135',
            //     'Direction_1_Nom_et_Prenoms' => 'Georges Al gharid',
            //     'Direction_1_Contact' => '0708000577',
            //     'Direction_2_Nom_et_Prenoms' => 'Konan Melanie',
            //     'Direction_2_Contact' => '0758732521',
            //     'Direction_3_Nom_et_Prenoms' => 'Ahissa Esther',
            //     'Direction_3_Contact' => '0705975807',
            //     'Adresse' => 'Rue G36, Abidjan Marcory',
            //     'Commune' => 'Abidjan',
            //     'Quartier' => 'Marcory Zone 4',
            //     'Rue' => 'Rue G36',
            //     'Zone' => 'Zone 4',
            //     'Autre' => 'Apres l\'hotel la Venue',
            // ],
    );

    }
}
