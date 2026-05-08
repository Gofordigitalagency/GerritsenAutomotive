<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Lokale demo-seeder met alle 87 auto's uit de productie-aanbod.
 * Behoudt de originele inconsistenties (CITROEN/Citroen, "C1"/"CITROEN C1",
 * typo's "Volkwagen"/"Hyndai") zodat we de matching-logica realistisch testen.
 *
 * Run: php artisan db:seed --class=OccasionDemoSeeder
 */
class OccasionDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Foreign-key safe: DELETE i.p.v. TRUNCATE (reclame_items wijst naar occasions)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('occasions')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // [merk, model, type, transmissie, bouwjaar, brandstof, tellerstand, prijs, sold]
        $cars = [
            // ---- Beschikbaar (21) ----
            ['OPEL',          'MERIVA-A',     '1.6 16V Temptation',     'Handgeschakeld', 2007, 'Benzine', 248445, 1795, false],
            ['CITROEN',       'C3',           '1.4 Essentiel',          'Handgeschakeld', 2010, 'Benzine', 176445, 1995, false],
            ['MERCEDES-BENZ', 'A 170',        'Elégance',               'Handgeschakeld', 2005, 'Benzine', 170805, 2495, false],
            ['VOLKSWAGEN',    'POLO',         '1.2 Easyline',           'Handgeschakeld', 2012, 'Benzine', 176334, 5245, false],
            ['LANCIA',        'YPSILON',      '1.2',                    'Handgeschakeld', 2009, 'Benzine', 185334, 1495, false],
            ['CHEVROLET',     'TACUMA',       '1.6 16V Spirit',         'Handgeschakeld', 2005, 'Benzine',  89854, 1445, false],
            ['SUZUKI',        'SX4',          '1.6 16V Shogun',         'Handgeschakeld', 2009, 'Benzine', 235447, 2495, false],
            ['HYUNDAI',       'ATOS',         '1.0i GLS',               'Automaat',       2003, 'Benzine', 130833, 2145, false],
            ['PEUGEOT',       '107',          '1.0 12V XS',             'Handgeschakeld', 2007, 'Benzine', 204779, 1995, false],
            ['FORD',          'FIESTA',       '1.0 Titanium',           'Handgeschakeld', 2015, 'Benzine', 144403, 6245, false],
            ['DAIHATSU',      'SIRION',       '2 1.0 12V',              'Handgeschakeld', 2008, 'Benzine', 163447, 2495, false],
            ['CITROEN',       'C1',           '1.0-12V Ambiance',       'Handgeschakeld', 2007, 'Benzine', 255557, 2245, false],
            ['FIAT',          '500',          '0.9 TwinAir Pop Turbo',  'Handgeschakeld', 2012, 'Benzine', 169608, 4495, false],
            ['CITROEN',       'CITROEN C1',   '1.0 12V Exclusive',      'Handgeschakeld', 2011, 'Benzine', 256443, 2445, false],
            ['AUDI',          'A4',           '2.0 Limousine',          'Handgeschakeld', 2003, 'Benzine', 251887, 2245, false],
            ['OPEL',          'AGILA',        '1.2 16V Enjoy',          'Handgeschakeld', 2003, 'Benzine', 183502, 1295, false],
            ['SUZUKI',        'SX4',          '1.6 Comfort',            'Handgeschakeld', 2006, 'Benzine', 235502, 2295, false],
            ['OPEL',          'AGILA',        '1.2 Enjoy',              'Handgeschakeld', 2008, 'Benzine', 227729, 1895, false],
            ['RENAULT',       'MODUS',        '1.4 16V',                'Handgeschakeld', 2006, 'Benzine', 183558, 1745, false],
            ['TOYOTA',        'AYGO',         '1.0 12V Sport',          'Handgeschakeld', 2007, 'Benzine', 329771, 1845, false],
            ['Volkwagen',     'Fox',          '1.4 16V',                'Handgeschakeld', 2006, 'Benzine', 142779, 1995, false],

            // ---- Verkocht (66) ----
            ['PEUGEOT',       '107',          '1.0 16V Sublime',                'Handgeschakeld', 2009, 'Benzine', 235221, 2245, true],
            ['RENAULT',       'TWINGO',       '1.2 16V Helios',                 'Handgeschakeld', 2002, 'Benzine', 183445, 1445, true],
            ['TOYOTA',        'AYGO',         '1.0 12V Comfort',                'Handgeschakeld', 2010, 'Benzine', 156003, 2745, true],
            ['RENAULT',       'TWINGO',       '1.2 16V Collection',             'Handgeschakeld', 2011, 'Benzine', 133401, 2745, true],
            ['PEUGEOT',       '206',          'Gentry premium',                 'Automaat',       2002, 'Benzine', 110103, 2495, true],
            ['PEUGEOT',       '107',          '1.0 XS Sport',                   'Handgeschakeld', 2010, 'Benzine', 137445, 3145, true],
            ['VOLKSWAGEN',    'GOLF',         '1.4 FSI Comfortline',            'Handgeschakeld', 2004, 'Benzine', 221447, 1645, true],
            ['PEUGEOT',       '207',          'SW 1.6 VTi XS Sport',            'Handgeschakeld', 2008, 'Benzine', 165982, 2995, true],
            ['CITROEN',       'CITROEN C1',   '1.0 12V',                        'Handgeschakeld', 2012, 'Benzine', 204566, 1945, true],
            ['CITROEN',       'C1',           '1.0-12V Séduction',              'Handgeschakeld', 2010, 'Benzine', 241668, 1745, true],
            ['SUZUKI',        'WAGON R+',     '1.3 GLS',                        'Handgeschakeld', 2002, 'Benzine', 173440, 1295, true],
            ['PEUGEOT',       '207',          'SW 1.4 VTi Style',               'Handgeschakeld', 2010, 'Benzine', 148776, 2675, true],
            ['RENAULT',       'TWINGO',       '1.2-16V Collection',             'Handgeschakeld', 2011, 'Benzine', 186029, 2745, true],
            ['PEUGEOT',       '207',          'SW 1.6 VTi XS Première',         'Handgeschakeld', 2007, 'Benzine', 149961, 2795, true],
            ['DAIHATSU',      'CUORE',        '1.0 Comfort',                    'Handgeschakeld', 2009, 'Benzine', 196775, 1495, true],
            ['OPEL',          'MERIVA-A',     '1.6 16V',                        'Handgeschakeld', 2005, 'Benzine', 144557, 1845, true],
            ['VOLKSWAGEN',    'GOLF',         '1.2 TSI Tour 2 blue motion',     'Handgeschakeld', 2011, 'Benzine', 221315, 3995, true],
            ['OPEL',          'MERIVA-A',     '1.6 16V',                        'Handgeschakeld', 2006, 'Benzine', 234558, 1745, true],
            ['RENAULT',       'TWINGO',       '1.2 Initaile',                   'Handgeschakeld', 2000, 'Benzine', 151447, 1645, true],
            ['SUZUKI',        'ALTO',         '1.1 GLS',                        'Handgeschakeld', 2002, 'Benzine', 200553, 1245, true],
            ['CITROEN',       'CITROEN C1',   '1.0 Collection',                 'Handgeschakeld', 2013, 'Benzine', 167445, 3995, true],
            ['VOLKSWAGEN',    'GOLF',         '1.4 16V TFSI',                   'Handgeschakeld', 2009, 'Benzine', 251402, 3645, true],
            ['CHEVROLET',     'SPARK',        '1.0 16V LT + Bi-Fuel',           'Handgeschakeld', 2011, 'Benzine',  74520, 3595, true],
            ['RENAULT',       'TWINGO',       '1.2 Expression',                 'Handgeschakeld', 2001, 'Benzine', 197701, 1445, true],
            ['SUZUKI',        'WAGON R',      '1.3',                            'Handgeschakeld', 2004, 'Benzine', 154297, 1445, true],
            ['CITROEN',       'C3',           '1.4i',                           'Handgeschakeld', 2008, 'Benzine', 137720, 1645, true],
            ['OPEL',          'AGILA',        '1.2 16V Enjoy',                  'Handgeschakeld', 2004, 'Benzine', 204558, 1495, true],
            ['SEAT',          'AROSA',        '1.4 MPI',                        'Automaat',       2005, 'Benzine',  81437, 1995, true],
            ['PEUGEOT',       '207',          'SW VTi XS',                      'Handgeschakeld', 2008, 'Benzine', 148670, 2745, true],
            ['OPEL',          'MERIVA-A',     '1.6 16V Cosmo',                  'Handgeschakeld', 2010, 'Benzine', 188552, 2245, true],
            ['PEUGEOT',       '206',          '1.6-16V',                        'Handgeschakeld', 2001, 'Benzine', 148552, 1745, true],
            ['OPEL',          'AGILA',        '1.2 16V',                        'Handgeschakeld', 2001, 'Benzine', 204553, 1245, true],
            ['OPEL',          'CORSA-C',      '1.2 16V Silverline',             'Handgeschakeld', 2006, 'Benzine', 187559, 1745, true],
            ['PEUGEOT',       '107',          '1.0 12V',                        'Handgeschakeld', 2010, 'Benzine', 216437, 1845, true],
            ['DAIHATSU',      'SIRION',       '1.0 12V RLi',                    'Automaat',       2000, 'Benzine', 141883, 1245, true],
            ['CITROEN',       'C3',           '1.4i Ligne Prestige',            'Automaat',       2003, 'Benzine', 177223, 1745, true],
            ['RENAULT',       'TWINGO',       '1.2 Emotion',                    'Handgeschakeld', 2007, 'Benzine', 183579, 1595, true],
            ['RENAULT',       'TWINGO',       '1.2 Lazuli',                     'Handgeschakeld', 2004, 'Benzine', 166731, 1445, true],
            ['Opel',          'Corsa',        '1.2-16V comfort',                'Handgeschakeld', 2001, 'Benzine', 162624, 1745, true],
            ['Citroen',       'C1',           '1.0-12V Ambiance',               'Handgeschakeld', 2008, 'Benzine', 169883, 2245, true],
            ['Suzuki',        'Swift',        '4Grip 4X4',                      'Handgeschakeld', 2008, 'Benzine', 202673, 3245, true],
            ['Peugeot',       '206',          '1.4 16V XT',                     'Automaat',       1999, 'Benzine', 139887, 1845, true],
            ['Peugeot',       '207',          '1.4 VTi Cool \'n Blue',          'Handgeschakeld', 2008, 'Benzine', 191447, 1845, true],
            ['Volkswagen',    '1.0 MPI',      'Cambridge',                      'Handgeschakeld', 2003, 'Benzine', 204669, 1495, true],
            ['Nissan',        'Almera Tino',  '1.8 Acenta',                     'Handgeschakeld', 2005, 'Benzine', 224879, 1495, true],
            ['Renault',       'Clio',         '1.4 16V MTV Edition',            'Handgeschakeld', 2000, 'Benzine', 141887, 1445, true],
            ['Peugeot',       '307',          '1.6 16V XT',                     'Handgeschakeld', 2003, 'Benzine', 196778, 1495, true],
            ['Citroen',       'C1',           '1.0 Ambiance',                   'Handgeschakeld', 2007, 'Benzine', 225194, 1295, true],
            ['Suzuki',        'Alto',         '1.1 GL',                         'Handgeschakeld', 2003, 'Benzine', 206773,  895, true],
            ['Citroen',       'C3',           '1.4i',                           'Handgeschakeld', 2005, 'Benzine', 197423, 1495, true],
            ['Renault',       'Twingo',       '1.2 16v Privilege',              'Handgeschakeld', 2003, 'Benzine', 162558, 1495, true],
            ['Opel',          'Corsa',        '1.4 16V',                        'Handgeschakeld', 2009, 'Benzine', 172448, 2495, true],
            ['Seat',          'Arosa Stella', '1.4 MPI',                        'Handgeschakeld', 2003, 'Benzine', 231226, 1245, true],
            ['Volkswagen',    'Lupo',         '1.4 16V',                        'Automaat',       2001, 'Benzine', 206887, 1745, true],
            ['Peugeot',       '206',          '1.6 16V Gentry',                 'Handgeschakeld', 2006, 'Benzine', 152786, 1845, true],
            ['Nissan',        'Micra',        '1.4 Clair CVT',                  'Automaat',       2002, 'Benzine', 170225, 1845, true],
            ['Seat',          'Arosa Stella', '1.4 MPI',                        'Handgeschakeld', 2005, 'Benzine', 153447, 1845, true],
            ['Peugeot',       '206',          '1.4 Air-Line',                   'Handgeschakeld', 2006, 'Benzine', 173886, 1845, true],
            ['Hyndai',        'Getz',         '1.1 Active Young',               'Handgeschakeld', 2006, 'Benzine', 173323, 1495, true],
            ['Opel',          'Agila',        '1.2 16V Color Edition',          'Handgeschakeld', 2003, 'Benzine', 117885, 1645, true],
            ['Chevrolet',     'Matiz',        '0.8 Pure',                       'Handgeschakeld', 2009, 'Benzine', 178669, 1495, true],
            ['Ford',          'KA',           '1.3 Cool & Sound',               'Handgeschakeld', 2008, 'Benzine', 110258, 1495, true],
            ['Peugeot',       '107',          'Urban Move',                     'Handgeschakeld', 2010, 'Benzine', 153226, 3595, true],
            ['Daihatsu',      'Sirion 2',     '1.0 12V',                        'Handgeschakeld', 2007, 'Benzine', 183887, 1995, true],
            ['Renault',       'Twingo',       '1.2 16V Collection',             'Handgeschakeld', 2011, 'Benzine', 166235, 2945, true],
            ['Renault',       'Twingo',       '1.2-16v Collection',             'Handgeschakeld', 2011, 'Benzine', 169957, 2995, true],
        ];

        $now = now();
        foreach ($cars as $i => $row) {
            [$merk, $model, $type, $trans, $jaar, $brandstof, $km, $prijs, $sold] = $row;

            // Behoud de originele "(VERKOCHT)" suffix in het model-veld zoals in productie staat.
            $modelStored = $sold ? $model . ' (VERKOCHT)' : $model;

            $slug = Str::slug("{$merk}-{$modelStored}-{$type}-{$jaar}") . '-' . ($i + 1);

            DB::table('occasions')->insert([
                'merk'        => $merk,
                'model'       => $modelStored,
                'type'        => $type,
                'slug'        => $slug,
                'transmissie' => $trans,
                'brandstof'   => $brandstof,
                'tellerstand' => $km,
                'bouwjaar'    => $jaar,
                'prijs'       => $prijs,
                'binnenkort'  => false,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        $this->command->info('Seeded ' . count($cars) . ' occasions.');
    }
}
