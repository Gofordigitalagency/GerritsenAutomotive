<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vakantie / sluitingsperiode
    |--------------------------------------------------------------------------
    | Tijdens deze periode kunnen bezoekers geen afspraken/reserveringen boeken
    | (werkplaats-widget, /reserveren en leenauto) en verschijnt er sitebreed een
    | melding. 'from' en 'to' zijn inclusief (t/m). Zet 'enabled' op false zodra
    | de vakantie voorbij is (de banner verdwijnt sowieso automatisch ná 'to').
    |
    | Datums in Y-m-d.
    */
    'enabled' => true,
    'from'    => '2026-07-29',
    'to'      => '2026-08-10',
    'message' => 'Wij zijn met vakantie t/m 10 augustus — reserveren en afspraken kunnen weer vanaf 11 augustus.',
];
