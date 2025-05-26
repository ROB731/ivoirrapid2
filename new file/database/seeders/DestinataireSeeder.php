<?php

namespace Database\Seeders;

use App\Models\Destinataire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinataireSeeder extends Seeder
{
    public function run()
    {
        $destinataires = [
            [
                'name' => 'SOCIDA LOCATION',
                'telephone' => '27 21 54 88 77',
                'contact' => '07 01 37 23 75',
                'email' => 'fatoumata.yeo@gbh.fr',
                'adresse' => 'Marcory VGE',
                'zone' => '006',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'ICOGEP EQUIPEMENTS',
                'telephone' => '0758474224',
                'contact' => '2721258154',
                'email' => 'maria.khayat@icogep.equipements.ci',
                'adresse' => 'Treichville Rue des pecheurs',
                'zone' => '021-022',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'PAPICI- TOP BURO',
                'telephone' => '27 21 28 20 78 /79 /80',
                'contact' => '27 21 28 20 78 /79 /80',
                'email' => 'scpapici@topburo.net',
                'adresse' => 'Marcory Boulevard VGE',
                'zone' => '006',
                'autre' => 'Marcory Boulevard VGE',
                'user_id' => 2,
            ],
            [
                'name' => 'SAINT-GOBAIN COTE D’IVOIRE',
                'telephone' => '0777443696',
                'contact' => '0700365800',
                'email' => 'Wilfried-Olivier.Foua@saint-gobain.com',
                'adresse' => 'TREICHVILLE',
                'zone' => '021-022',
                'autre' => 'Rue des Forreurs',
                'user_id' => 2,
            ],
            [
                'name' => 'HELP INDUSTRIES',
                'telephone' => '2721357548',
                'contact' => null,
                'email' => 'helpindustrie.ci@gmail.com',
                'adresse' => 'Treichville',
                'zone' => '023',
                'autre' => '13e rue des selliers juste derriere cacomiaf',
                'user_id' => 2,
            ],
            [
                'name' => 'SPIRAL',
                'telephone' => '2721750600',
                'contact' => null,
                'email' => 'spiraloffice.commercial@spiral.ci',
                'adresse' => 'Rue de l\'industrie',
                'zone' => '021-022',
                'autre' => null,
                'user_id' => 2,
            ],
            [
                'name' => 'SI BETON',
                'telephone' => '0757202020',
                'contact' => null,
                'email' => 'sibetons@sibetons.com',
                'adresse' => 'Attecoube Banco Nord',
                'zone' => '151-153-154-158	',
                'autre' => null,
                'user_id' => 2,
            ],
            [
                'name' => 'AFRITRANS SARL',
                'telephone' => '0777677067',
                'contact' => '0788881226',
                'email' => 'info@afritrans-ci.com',
                'adresse' => 'Treichville, Boulevard de Marseille',
                'zone' => '024',
                'autre' => 'En face du parc des sports',
                'user_id' => 2,
            ],
            [
                'name' => 'G.T.T.P MONDIAL VOYAGES',
                'telephone' => '2721213568',
                'contact' => '0707076346',
                'email' => 'prima@mondialci.com',
                'adresse' => 'Marcory 01 BP 3864 ABIDJAN 01',
                'zone' => '002',
                'autre' => 'Prima Center, Immeuble les terrasses de Prima, au rez-de-chaussée, en face de Pistache et chocolat.',
                'user_id' => 2,
            ],
            [
                'name' => 'UNIVERSELLE INDUSTRIES',
                'telephone' => '0505453000',
                'contact' => '0554222084 SERVICE RECOUVREMENT',
                'email' => 'service.recouvrement@universelleindustries.com',
                'adresse' => 'Yopougon zone industrielle',
                'zone' => '151-153-154-158	',
                'autre' => 'Partant de l\'entrée de la zone 3e carrefour a droite',
                'user_id' => 2,
            ],
            [
                'name' => 'FOX COOLING',
                'telephone' => '2721248201',
                'contact' => '07779538051',
                'email' => 'savci@foxcooling.com',
                'adresse' => 'Marcory Boulevard VGE',
                'zone' => '001',
                'autre' => 'En face la gare de sotra de koumassi',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'BATIPLUS ELECTRICITÉ',
                'telephone' => '2721758602',
                'contact' => '0757422222',
                'email' => 'sherine.abdulghani@grupebatimat.com',
                'adresse' => 'TREICHVILLE BOULEVARD DE MARSEILLES',
                'zone' => '024',
                'autre' => 'Dans l\'alignement de le nouvelle pharmacie du palais des sports',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'CIMAS CI',
                'telephone' => '0707781058',
                'contact' => '0141663903',
                'email' => 'cimascomptabilite@gmail.com',
                'adresse' => 'Treichville en face de Ivoire ',
                'zone' => '024',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SOGELEC',
                'telephone' => '2721241027',
                'contact' => '2721241027',
                'email' => 'sogelec@sogelec-ci.com',
                'adresse' => 'Treichville Boulevard de Marseille ',
                'zone' => '024',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'AFRIQUE FROID',
                'telephone' => '2721514072',
                'contact' => '0565341542',
                'email' => 'info@afriquefroid.com',
                'adresse' => 'Marcory',
                'zone' => '005',
                'autre' => 'Dans le perimetre de la banque Orabanque sous l\"echangeur Marcory',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'BAZZI CI',
                'telephone' => '0749122034',
                'contact' => '2721218585',
                'email' => 'diom.abou@bazzi.co',
                'adresse' => 'Marcory',
                'zone' => '005',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'NOVALIS ',
                'telephone' => '2723430597',
                'contact' => '0708082203',
                'email' => 'rose.amon@novalis-ci.com',
                'adresse' => 'Yopougon Koute Koute Beago',
                'zone' => '157',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'FRIESLAND CAMPINA ',
                'telephone' => '0586088896',
                'contact' => null,
                'email' =>  'Myriam.Diouf@frieslandcampina.com',
                'adresse' => 'zone industrielle Yopougon',
                'zone' => '151-153-154-158	',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            
            [
                'name' => 'ACIPAC',
                'telephone' => '2723466599',
                'contact' => '0585040404',
                'email' =>  'acipac@ivoirrapid.com',
                'adresse' => 'Zone Industrielle de Yopougon',
                'zone' => '151-153-154-158	',
                'autre' => 'A coté de la direction de la Nouvelle Parfumerie Gandour',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SIMAM CI SA',
                'telephone' => '2723408560',
                'contact' => null,
                'email' =>  'simam@simamci.com',
                'adresse' => 'Zone Industrielle de Yopougon',
                'zone' => '159',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'DREAM COSMETICS',
                'telephone' => '2723535999',
                'contact' => '2723535999',
                'email' =>  's.dosso@dreamcosmetics.net',
                'adresse' => 'Zone Industrielle de Yopougon',
                'zone' => '152',
                'autre' => 'Andokoi face depot sotra',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'NOUVELLE MICI EMBACI',
                'telephone' => '2721598800',
                'contact' => '0720489872',
                'email' =>  'info@nme.com.ci',
                'adresse' => 'Koumassi',
                'zone' => '041',
                'autre' => 'Zone Industrielle de Apres soweto au village en face de Sotici 02',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'LE JOINT IVOIRIEN',
                'telephone' => '2721599595',
                'contact' => '2721599595',
                'email' =>  'lejointivoirien@outlook.fr',
                'adresse' => 'Koumassi Rue des sciences',
                'zone' => '041',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SIPPEC',
                'telephone' => '2723536464',
                'contact' => '0707108671',
                'email' =>  'attioua.kouame@sippec.com',
                'adresse' => 'Zone industrielle de Yopougon',
                'zone' => '151-153-154-158	',
                'autre' => 'Route n\'dotre juste apres la cite ADO en venant du carrefour zone',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'PUBLIKA',
                'telephone' => '0778363669',
                'contact' => '0778363669',
                'email' =>  'secretariat@pulbika.ci',
                'adresse' => 'Treichville en face de pacoci',
                'zone' => '021-022',
                'autre' => 'rue des pecheurs',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SPRIINT TECHNIQUE',
                'telephone' => '0546048242',
                'contact' => '0546048241',
                'email' =>  'leroulementivoirien@gmail.com',
                'adresse' => 'Marcory Rue pierre marie curie',
                'zone' => '002',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'CIVADIS SA',
                'telephone' => '0788690227',
                'contact' => '0707201802',
                'email' =>  'facturation@civadis-ci.com',
                'adresse' => 'Marcory Rue pierre marie curie',
                'zone' => '002',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'VISUEL CONCEPTS',
                'telephone' => '2721251793',
                'contact' => '0759399587',
                'email' =>  'info@visuelconcepts.com',
                'adresse' => 'Marcory Rue mercedes',
                'zone' => '002',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SIFA CI',
                'telephone' => '0714084610',
                'contact' => '0714084610',
                'email' =>  'yrdjezou@sifalogistics.com',
                'adresse' => 'Marcory',
                'zone' => '002',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SOFID',
                'telephone' => '2721351535',
                'contact' => '0707069992',
                'email' =>  'contact@sofidci.com',
                'adresse' => 'Marcory Rue PMC',
                'zone' => '002',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'ACI',
                'telephone' => '0708087243',
                'contact' => '0708883411',
                'email' =>  'michelle.kouadio@acichimie.com',
                'adresse' => 'Marcory',
                'zone' => '002',
                'autre' => null,
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SOCIDA',
                'telephone' => '2721214000',
                'contact' => '2721214010',
                'email' =>  'laeticia.andoh@gbh.fr',
                'adresse' => 'Marcory Rue pierre marie curie',
                'zone' => '002',
                'autre' => 'Entre Bosh et pharmacie Marie curie',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'IPB',
                'telephone' => '2721216380',
                'contact' => '0748090054',
                'email' =>  'ipbcontact@ipb.ci',
                'adresse' => 'Koumassi ',
                'zone' => '002',
                'autre' => 'Zone industrielle non loin du feu de Mesano',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            [
                'name' => 'SEMAG MATFORCE',
                'telephone' => '2721758890',
                'contact' => '0546048240',
                'email' =>  'direction@matforce.ci',
                'adresse' => 'Zone industrielle Vridi ',
                'zone' => '064',
                'autre' => 'Rue de la pointe aux fumeurs seteur du tripostal',
                'user_id' => 2, // Remplacez par l'ID de l'utilisateur auquel le destinataire est associé
            ],
            
        ];

         // Insérer les destinataires
         foreach ($destinataires as $destinataire) {
            Destinataire::updateOrCreate(
                ['email' => $destinataire['email']], // Utiliser l'email comme critère unique
                $destinataire
            );
        }
    }
}