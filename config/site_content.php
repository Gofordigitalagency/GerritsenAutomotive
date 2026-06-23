<?php

/**
 * Schema voor de bewerkbare homepage-content & theme.
 * Elk veld heeft: type, label, default, optioneel help-tekst.
 *
 * Velden lezen vanuit blade: {{ \App\Models\SiteSetting::get('hero.title_line1') }}
 * Of via de helper:           {{ setting('hero.title_line1') }}
 *
 * Types die de admin-UI ondersteunt:
 *   text     — kort tekstveld
 *   longtext — textarea
 *   color    — kleurkiezer
 *   image    — bestandsupload (relatief pad in storage/app/public)
 *   phone    — telefoonnummer
 *   email    — e-mailadres
 *   url      — URL
 */

return [

    // ====================================================================
    // 01. Theme & kleuren
    // ====================================================================
    'theme' => [
        'label' => 'Thema & kleuren',
        'icon'  => '🎨',
        'fields' => [
            'theme.bg'            => ['type' => 'color', 'label' => 'Hoofdachtergrond',          'default' => '#0b0c10', 'help' => 'De donkere basis-achtergrond van de hele site.'],
            'theme.bg_alt'        => ['type' => 'color', 'label' => 'Afwisselende achtergrond',  'default' => '#11131a', 'help' => 'Voor om-en-om secties (lichter dan hoofd).'],
            'theme.surface'       => ['type' => 'color', 'label' => 'Card-achtergrond',          'default' => '#161922'],
            'theme.fg'            => ['type' => 'color', 'label' => 'Primaire tekst',            'default' => '#f4f5f8'],
            'theme.fg_muted'      => ['type' => 'color', 'label' => 'Subtekst',                  'default' => '#8a8d99'],
            'theme.accent'        => ['type' => 'color', 'label' => 'Accentkleur (rood)',        'default' => '#e63946', 'help' => 'Knoppen, links, dots, hover-states.'],
            'theme.accent_soft'   => ['type' => 'color', 'label' => 'Accent licht',              'default' => '#ff6b6b'],
            'theme.border'        => ['type' => 'color', 'label' => 'Lijnkleur',                 'default' => 'rgba(255,255,255,.08)'],
        ],
    ],

    // ====================================================================
    // 02. Hero (bovenste blok van de pagina)
    // ====================================================================
    'hero' => [
        'label' => 'Hero (bovenkant pagina)',
        'icon'  => '🎬',
        'fields' => [
            'hero.eyebrow'       => ['type' => 'text',     'label' => 'Eyebrow boven titel',  'default' => 'Gerritsen Automotive · Arnhem'],
            'hero.title_line1'   => ['type' => 'text',     'label' => 'Titel — regel 1',      'default' => 'Uw partner in'],
            'hero.title_accent'  => ['type' => 'text',     'label' => 'Titel — accent woord', 'default' => 'betrouwbare', 'help' => 'Dit woord krijgt de accentkleur.'],
            'hero.title_line2'   => ['type' => 'text',     'label' => 'Titel — slot',         'default' => 'occasions.'],
            'hero.sub'           => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => "Zorgvuldig geselecteerde auto's, eerlijk advies en een eigen werkplaats.\nAlles onder één dak in Arnhem."],
            'hero.cta_primary'   => ['type' => 'text',     'label' => 'Primaire knop tekst',  'default' => 'Bekijk occasions',    'help' => 'Het aantal occasions wordt automatisch ervoor geplaatst.'],
            'hero.cta_secondary' => ['type' => 'text',     'label' => 'Secundaire knop',      'default' => 'Contact'],
            'hero.bg_image'      => ['type' => 'image',    'label' => 'Achtergrondfoto',      'default' => 'images/backgroundhome.jpg'],
        ],
    ],

    // ====================================================================
    // 03. Over ons
    // ====================================================================
    'over' => [
        'label' => 'Over ons (onderaan pagina)',
        'icon'  => '👥',
        'fields' => [
            'over.eyebrow'        => ['type' => 'text',     'label' => 'Eyebrow',           'default' => 'Over ons'],
            'over.title'          => ['type' => 'text',     'label' => 'Titel',             'default' => 'Een klein team. Een hele garage.'],
            'over.body_p1'        => ['type' => 'longtext', 'label' => 'Alinea 1',          'default' => 'Bij Gerritsen Automotive in Arnhem ben je geen klantnummer. Je hebt direct contact met de mensen die de auto kennen, repareren en verkopen.'],
            'over.body_p2'        => ['type' => 'longtext', 'label' => 'Alinea 2',          'default' => 'Persoonlijk advies, duidelijke prijzen en alles op één locatie: verkoop, werkplaats en verhuur. Loop binnen, bel of stuur een berichtje, we helpen je graag.'],
            'over.image'          => ['type' => 'image',    'label' => 'Foto (rechts)',     'default' => 'images/handshake.jpg'],
            'over.person1_name'   => ['type' => 'text',     'label' => 'Persoon 1 — naam',  'default' => 'Shania'],
            'over.person1_role'   => ['type' => 'text',     'label' => 'Persoon 1 — rol',   'default' => 'Verkoop'],
            'over.person2_name'   => ['type' => 'text',     'label' => 'Persoon 2 — naam',  'default' => 'Mick'],
            'over.person2_role'   => ['type' => 'text',     'label' => 'Persoon 2 — rol',   'default' => 'Werkplaats'],
        ],
    ],

    // ====================================================================
    // 04. Contact
    // ====================================================================
    'contact' => [
        'label' => 'Contact & openingstijden',
        'icon'  => '📍',
        'fields' => [
            'contact.address'        => ['type' => 'text',  'label' => 'Adres',              'default' => 'Gelderse Rooslaan 14 A, 6841 BE Arnhem'],
            'contact.phone_sales'    => ['type' => 'phone', 'label' => 'Telefoon Verkoop',   'default' => '0638257987'],
            'contact.phone_workshop' => ['type' => 'phone', 'label' => 'Telefoon Werkplaats','default' => '0649951874'],
            'contact.email'          => ['type' => 'email', 'label' => 'E-mailadres',        'default' => 'info@gerritsenautomotive.nl'],
            'contact.hours_weekday'  => ['type' => 'text',  'label' => 'Openingstijden ma-vr','default' => 'Ma t/m vr 08:30 – 17:30'],
            'contact.hours_saturday' => ['type' => 'text',  'label' => 'Openingstijden za',  'default' => 'Za 09:00 – 16:00'],
            'contact.hours_sunday'   => ['type' => 'text',  'label' => 'Openingstijden zo',  'default' => 'Zo gesloten'],
        ],
    ],

    // ====================================================================
    // Aanbod-pagina (/aanbod)
    // ====================================================================
    'aanbod_page' => [
        'label' => 'Aanbod-pagina',
        'icon'  => '🚙',
        'fields' => [
            'aanbod_page.bg_image'    => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/autos.jpeg'],
            'aanbod_page.eyebrow'     => ['type' => 'text',     'label' => 'Eyebrow',          'default' => 'Ons aanbod'],
            'aanbod_page.title'       => ['type' => 'text',     'label' => 'Paginatitel',      'default' => 'Alle occasions in voorraad.'],
            'aanbod_page.subtitle'    => ['type' => 'longtext', 'label' => 'Sub-tekst',        'default' => "Zorgvuldig geselecteerde auto's. Zoek op merk, filter op prijs of brandstof, en vind de auto die bij je past."],
            'aanbod_page.empty_title' => ['type' => 'text',     'label' => 'Lege staat — kop', 'default' => 'Geen occasions binnen deze filters.'],
            'aanbod_page.empty_sub'   => ['type' => 'longtext', 'label' => 'Lege staat — uitleg', 'default' => 'Probeer een andere filter-combinatie of bel ons voor persoonlijk advies.'],
        ],
    ],

    // ====================================================================
    // Werkplaats-pagina (/werkplaats)
    // ====================================================================
    'werkplaats_page' => [
        'label' => 'Werkplaats-pagina',
        'icon'  => '🛠️',
        'fields' => [
            'werkplaats_page.bg_image'    => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/afspraak-banner.jpg'],
            'werkplaats_page.eyebrow'     => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Werkplaats'],
            'werkplaats_page.title'       => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Onderhoud, APK en reparatie.'],
            'werkplaats_page.subtitle'    => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => "Vul je kenteken in en plan je afspraak in 3 stappen. Wij regelen de rest."],

            'werkplaats_page.booking_eyebrow' => ['type' => 'text', 'label' => 'Booking-blok eyebrow',  'default' => 'Afspraak plannen'],
            'werkplaats_page.booking_title'   => ['type' => 'text', 'label' => 'Booking-blok titel',    'default' => 'Plan in 3 stappen.'],
            'werkplaats_page.booking_sub'     => ['type' => 'longtext','label' => 'Booking-blok sub',   'default' => "Direct gekoppeld met de RDW. Geen account nodig."],

            'werkplaats_page.services_eyebrow' => ['type' => 'text',     'label' => 'Diensten — eyebrow', 'default' => 'Wat wij doen'],
            'werkplaats_page.services_title'   => ['type' => 'text',     'label' => 'Diensten — titel',   'default' => 'Eén werkplaats voor alles.'],
            'werkplaats_page.services_sub'     => ['type' => 'longtext', 'label' => 'Diensten — sub',     'default' => 'Van APK tot reparatie. Persoonlijk contact met de werkplaats.'],

            'werkplaats_page.svc1_image' => ['type' => 'image',    'label' => 'Dienst 1 — foto',  'default' => ''],
            'werkplaats_page.svc1_title' => ['type' => 'text',     'label' => 'Dienst 1 — titel', 'default' => 'APK keuring'],
            'werkplaats_page.svc1_body'  => ['type' => 'longtext', 'label' => 'Dienst 1 — tekst', 'default' => 'Verplichte jaarlijkse keuring volgens RDW-richtlijnen.'],
            'werkplaats_page.svc2_image' => ['type' => 'image',    'label' => 'Dienst 2 — foto',  'default' => ''],
            'werkplaats_page.svc2_title' => ['type' => 'text',     'label' => 'Dienst 2 — titel', 'default' => 'Onderhoudsbeurt'],
            'werkplaats_page.svc2_body'  => ['type' => 'longtext', 'label' => 'Dienst 2 — tekst', 'default' => 'Kleine en grote beurten. Olie, filters en alle vloeistoffen.'],
            'werkplaats_page.svc3_image' => ['type' => 'image',    'label' => 'Dienst 3 — foto',  'default' => ''],
            'werkplaats_page.svc3_title' => ['type' => 'text',     'label' => 'Dienst 3 — titel', 'default' => 'Reparatie & diagnose'],
            'werkplaats_page.svc3_body'  => ['type' => 'longtext', 'label' => 'Dienst 3 — tekst', 'default' => 'Foutcodes uitlezen, motor- en remwerk, distributie.'],
            'werkplaats_page.svc4_image' => ['type' => 'image',    'label' => 'Dienst 4 — foto',  'default' => ''],
            'werkplaats_page.svc4_title' => ['type' => 'text',     'label' => 'Dienst 4 — titel', 'default' => 'Aankoopkeuring'],
            'werkplaats_page.svc4_body'  => ['type' => 'longtext', 'label' => 'Dienst 4 — tekst', 'default' => 'Technische check voor je een auto koopt.'],

            'werkplaats_page.usps_eyebrow' => ['type' => 'text',     'label' => 'USPs — eyebrow', 'default' => 'Waarom hier'],
            'werkplaats_page.usps_title'   => ['type' => 'text',     'label' => 'USPs — titel',   'default' => 'Persoonlijk en duidelijk.'],
            'werkplaats_page.usp1' => ['type' => 'text', 'label' => 'USP 1', 'default' => 'Heldere prijsafspraak vooraf'],
            'werkplaats_page.usp2' => ['type' => 'text', 'label' => 'USP 2', 'default' => 'Eigen werkplaats in Arnhem'],
            'werkplaats_page.usp3' => ['type' => 'text', 'label' => 'USP 3', 'default' => 'Direct contact met de monteur'],
            'werkplaats_page.usp4' => ['type' => 'text', 'label' => 'USP 4', 'default' => 'Online afspraak inplannen'],
            'werkplaats_page.usp5' => ['type' => 'text', 'label' => 'USP 5', 'default' => 'Persoonlijk advies'],
            'werkplaats_page.usp6' => ['type' => 'text', 'label' => 'USP 6', 'default' => ''],
        ],
    ],

    // ====================================================================
    // Binnenkort-pagina (/binnenkort)
    // ====================================================================
    'binnenkort_page' => [
        'label' => 'Binnenkort-pagina',
        'icon'  => '🚦',
        'fields' => [
            'binnenkort_page.bg_image' => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/autos.jpeg'],
            'binnenkort_page.eyebrow'  => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Binnenkort verwacht'],
            'binnenkort_page.title'    => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Auto\'s die er bijna zijn.'],
            'binnenkort_page.subtitle' => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => 'Deze auto\'s komen binnenkort beschikbaar. Eerste optie? Laat het ons weten.'],
            'binnenkort_page.empty_title' => ['type' => 'text',     'label' => 'Lege staat — kop',   'default' => 'Op dit moment geen auto\'s in aankomst.'],
            'binnenkort_page.empty_sub'   => ['type' => 'longtext', 'label' => 'Lege staat — uitleg','default' => 'Bekijk ons huidige aanbod of bel voor persoonlijk advies.'],
        ],
    ],

    // ====================================================================
    // Auto verkopen-pagina (/auto-verkopen)
    // ====================================================================
    'verkopen_page' => [
        'label' => 'Auto verkopen-pagina',
        'icon'  => '💰',
        'fields' => [
            'verkopen_page.bg_image' => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/handshake.jpg'],
            'verkopen_page.eyebrow'  => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Auto verkopen'],
            'verkopen_page.title'    => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Inkoop in 3 stappen.'],
            'verkopen_page.subtitle' => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => 'Vul je gegevens in, voeg een paar foto\'s toe en wij doen je een eerlijk bod.'],

            'verkopen_page.step1_title' => ['type' => 'text',     'label' => 'Stap 1 — titel', 'default' => 'Auto-gegevens'],
            'verkopen_page.step1_body'  => ['type' => 'longtext', 'label' => 'Stap 1 — tekst', 'default' => 'Kenteken, km-stand en eventuele opties.'],
            'verkopen_page.step2_title' => ['type' => 'text',     'label' => 'Stap 2 — titel', 'default' => 'Foto\'s'],
            'verkopen_page.step2_body'  => ['type' => 'longtext', 'label' => 'Stap 2 — tekst', 'default' => 'Een paar duidelijke foto\'s helpen ons een goede prijs te bepalen.'],
            'verkopen_page.step3_title' => ['type' => 'text',     'label' => 'Stap 3 — titel', 'default' => 'Contact'],
            'verkopen_page.step3_body'  => ['type' => 'longtext', 'label' => 'Stap 3 — tekst', 'default' => 'We nemen contact met je op om een afspraak in te plannen.'],

            'verkopen_page.help_text' => ['type' => 'longtext', 'label' => 'Hulp-tekst onderaan', 'default' => 'Liever direct bellen? We helpen je graag verder.'],
        ],
    ],

    // ====================================================================
    // Reserveer-pagina (/reserveren)
    // ====================================================================
    'reserveren_page' => [
        'label' => 'Reserveer-pagina',
        'icon'  => '📅',
        'fields' => [
            'reserveren_page.bg_image' => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/cargo-trailers-passenger-car-parked-spacious-lot.jpg'],
            'reserveren_page.eyebrow'  => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Reserveren'],
            'reserveren_page.title'    => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Plan je reservering online.'],
            'reserveren_page.subtitle' => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => 'Aanhanger, tapijtreiniger, koplampen polijsten of leenauto. Kies een datum en tijd, klaar.'],
            'reserveren_page.help_text' => ['type' => 'longtext', 'label' => 'Hulp-tekst onder formulier', 'default' => 'Vragen over een reservering? Bel of mail ons gerust.'],
        ],
    ],

    // ====================================================================
    // Auto-detailpagina (/occasions/{slug})
    // ====================================================================
    'occasion_page' => [
        'label' => 'Auto-detailpagina',
        'icon'  => '🚘',
        'fields' => [
            'occasion_page.cta_title'  => ['type' => 'text',     'label' => 'Bottom-CTA titel',  'default' => 'Interesse in deze auto?'],
            'occasion_page.cta_sub'    => ['type' => 'longtext', 'label' => 'Bottom-CTA sub',    'default' => 'Plan een proefrit of stuur een bericht. We reageren zo snel mogelijk.'],
            'occasion_page.cta_btn'    => ['type' => 'text',     'label' => 'Bottom-CTA knop',   'default' => 'Plan een proefrit'],
            'occasion_page.empty_text' => ['type' => 'text',     'label' => 'Tekst bij geen opties', 'default' => 'Geen opties opgegeven.'],
        ],
    ],

    // ====================================================================
    // Diensten-pagina (/diensten)
    // ====================================================================
    'diensten_page' => [
        'label' => 'Diensten-pagina',
        'icon'  => '🧰',
        'fields' => [
            'diensten_page.bg_image' => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/handshake.jpg'],
            'diensten_page.eyebrow'  => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Diensten'],
            'diensten_page.title'    => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Méér dan alleen verkoop.'],
            'diensten_page.subtitle' => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => 'Naast verkoop en werkplaats verhuren we praktische spullen voor thuis en rondom de auto.'],

            'diensten_page.leenauto_eyebrow' => ['type' => 'text', 'label' => 'Leenauto blok — eyebrow', 'default' => 'Uitgelicht'],
            'diensten_page.leenauto_title'   => ['type' => 'text', 'label' => 'Leenauto blok — titel',   'default' => 'Leenauto'],
            'diensten_page.leenauto_sub'     => ['type' => 'longtext', 'label' => 'Leenauto blok — sub', 'default' => 'Onze leenauto is direct beschikbaar voor verhuur.'],

            'diensten_page.verhuur_eyebrow' => ['type' => 'text',     'label' => 'Verhuur — eyebrow', 'default' => 'Verhuur en service'],
            'diensten_page.verhuur_title'   => ['type' => 'text',     'label' => 'Verhuur — titel',   'default' => 'Direct online te reserveren.'],
            'diensten_page.verhuur_sub'     => ['type' => 'longtext', 'label' => 'Verhuur — sub',     'default' => 'Reserveer eenvoudig een tijdslot. Ophalen en terugbrengen op de werkplaats in Arnhem.'],

            // Aanhanger
            'diensten_page.svc1_image'      => ['type' => 'image', 'label' => 'Aanhanger — foto',        'default' => 'images/cargo-trailers-passenger-car-parked-spacious-lot.jpg'],
            'diensten_page.svc1_tag'        => ['type' => 'text',  'label' => 'Aanhanger — tag',         'default' => 'Verhuur'],
            'diensten_page.svc1_title'     => ['type' => 'text',  'label' => 'Aanhanger — titel',       'default' => 'Aanhanger'],
            'diensten_page.svc1_desc'      => ['type' => 'longtext','label' => 'Aanhanger — tekst',      'default' => 'Veilig, schoon en direct beschikbaar. 130 × 250 cm laadbak.'],
            'diensten_page.svc1_price_lbl' => ['type' => 'text',  'label' => 'Aanhanger — prijslabel',  'default' => 'vanaf'],
            'diensten_page.svc1_price_amt' => ['type' => 'text',  'label' => 'Aanhanger — prijs',       'default' => '€ 15'],
            'diensten_page.svc1_price_meta'=> ['type' => 'text',  'label' => 'Aanhanger — prijs-suffix','default' => '/ 4 uur'],

            // Tapijtreiniger
            'diensten_page.svc2_image'     => ['type' => 'image', 'label' => 'Tapijtreiniger — foto',   'default' => 'images/1200x810.jpg'],
            'diensten_page.svc2_tag'       => ['type' => 'text',  'label' => 'Tapijtreiniger — tag',    'default' => 'Verhuur'],
            'diensten_page.svc2_title'     => ['type' => 'text',  'label' => 'Tapijtreiniger — titel',  'default' => 'Numatic George'],
            'diensten_page.svc2_desc'      => ['type' => 'longtext','label' => 'Tapijtreiniger — tekst','default' => 'Krachtige tapijtreiniger voor meubels, vloerkleden en interieur.'],
            'diensten_page.svc2_price_lbl' => ['type' => 'text',  'label' => 'Tapijtreiniger — prijslabel', 'default' => 'vanaf'],
            'diensten_page.svc2_price_amt' => ['type' => 'text',  'label' => 'Tapijtreiniger — prijs',  'default' => '€ 25'],
            'diensten_page.svc2_price_meta'=> ['type' => 'text',  'label' => 'Tapijtreiniger — prijs-suffix','default' => '/ dag'],

            // Koplampen
            'diensten_page.svc3_image'     => ['type' => 'image', 'label' => 'Koplampen — foto',        'default' => 'images/head-lights-car.jpg'],
            'diensten_page.svc3_tag'       => ['type' => 'text',  'label' => 'Koplampen — tag',         'default' => 'Service'],
            'diensten_page.svc3_title'     => ['type' => 'text',  'label' => 'Koplampen — titel',       'default' => 'Koplampen polijsten'],
            'diensten_page.svc3_desc'      => ['type' => 'longtext','label' => 'Koplampen — tekst',     'default' => 'Doffe of vergeelde koplampen weer helder. Resultaat binnen één behandeling.'],
            'diensten_page.svc3_price_lbl' => ['type' => 'text',  'label' => 'Koplampen — prijslabel',  'default' => 'op afspraak'],
            'diensten_page.svc3_price_amt' => ['type' => 'text',  'label' => 'Koplampen — prijs',       'default' => ''],
            'diensten_page.svc3_price_meta'=> ['type' => 'text',  'label' => 'Koplampen — prijs-suffix','default' => ''],
        ],
    ],

    // ====================================================================
    // Over ons-pagina (/over)
    // ====================================================================
    'over_page' => [
        'label' => 'Over ons-pagina',
        'icon'  => '👥',
        'fields' => [
            'over_page.bg_image' => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/handshake.jpg'],
            'over_page.eyebrow'  => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Over ons'],
            'over_page.title'    => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Een klein team. Een hele garage.'],
            'over_page.subtitle' => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => 'Persoonlijk advies, eigen werkplaats en alles op één locatie in Arnhem.'],

            'over_page.team_eyebrow' => ['type' => 'text', 'label' => 'Team-blok eyebrow', 'default' => 'Wie je spreekt'],
            'over_page.team_title'   => ['type' => 'text', 'label' => 'Team-blok titel',   'default' => 'Direct contact, korte lijnen.'],

            'over_page.gallery_eyebrow' => ['type' => 'text', 'label' => 'Galerij-blok eyebrow', 'default' => 'In beeld'],
            'over_page.gallery_title'   => ['type' => 'text', 'label' => 'Galerij-blok titel',   'default' => 'Onze garage in Arnhem.'],
            'over_page.gallery_image_1' => ['type' => 'image', 'label' => 'Galerij — foto 1', 'default' => 'images/autos.jpeg'],
            'over_page.gallery_image_2' => ['type' => 'image', 'label' => 'Galerij — foto 2', 'default' => 'images/afspraak-banner.jpg'],
            'over_page.gallery_image_3' => ['type' => 'image', 'label' => 'Galerij — foto 3', 'default' => 'images/handshake.jpg'],
        ],
    ],

    // ====================================================================
    // Contact-pagina (/contact)
    // ====================================================================
    'contact_page' => [
        'label' => 'Contact-pagina',
        'icon'  => '📨',
        'fields' => [
            'contact_page.bg_image' => ['type' => 'image',    'label' => 'Hero-achtergrondfoto', 'default' => 'images/backgroundhome.jpg'],
            'contact_page.eyebrow'  => ['type' => 'text',     'label' => 'Eyebrow',              'default' => 'Contact'],
            'contact_page.title'    => ['type' => 'text',     'label' => 'Paginatitel',          'default' => 'Loop binnen, bel of stuur een bericht.'],
            'contact_page.subtitle' => ['type' => 'longtext', 'label' => 'Sub-tekst',            'default' => 'Je krijgt direct contact met de mensen die de auto kennen, repareren en verkopen.'],

            'contact_page.form_eyebrow' => ['type' => 'text',     'label' => 'Formulier — eyebrow', 'default' => 'Stuur een bericht'],
            'contact_page.form_title'   => ['type' => 'text',     'label' => 'Formulier — titel',   'default' => 'Wat kunnen we voor je doen?'],
            'contact_page.form_sub'     => ['type' => 'longtext', 'label' => 'Formulier — sub',     'default' => 'Vul het formulier in en we reageren zo snel mogelijk.'],

            'contact_page.sfeer_image' => ['type' => 'image', 'label' => 'Sfeerfoto naast formulier', 'default' => 'images/handshake.jpg'],
            'contact_page.map_embed_url' => ['type' => 'url', 'label' => 'Google Maps embed URL', 'default' => '', 'help' => 'Plak hier de "embed" URL uit Google Maps (Delen → Insluiten op site → src). Leeg = geen kaart.'],
        ],
    ],

    // ====================================================================
    // 05. Leenauto / verhuur featured
    // ====================================================================
    'leenauto' => [
        'label' => 'Leenauto',
        'icon'  => '🚗',
        'fields' => [
            'leenauto.eyebrow'    => ['type' => 'text',     'label' => 'Eyebrow',                'default' => 'Leenauto'],
            'leenauto.title'      => ['type' => 'text',     'label' => 'Titel',                  'default' => 'Toyota Aygo Premium Edition'],
            'leenauto.subtitle'   => ['type' => 'text',     'label' => 'Tagline',                'default' => 'Compact rijden. Premium gevoel.'],
            'leenauto.price'      => ['type' => 'text',     'label' => 'Prijs-badge',            'default' => 'Vanaf € 35 per dag'],
            'leenauto.usps'       => ['type' => 'longtext', 'label' => 'Uitrusting & comfort',   'default' => "Apple CarPlay\nLederen interieur\nAirco\n5-deurs comfort\nElektrische ramen\nHandgeschakeld\nZuinig in verbruik\nOnbeperkte KM", 'help' => 'Eén punt per regel. Verschijnen met groene vinkjes.'],
            'leenauto.location'   => ['type' => 'text',     'label' => 'Beschikbaarheid-tekst',  'default' => 'Direct beschikbaar in Arnhem'],
            'leenauto.image_main' => ['type' => 'image',    'label' => 'Hoofdfoto',              'default' => 'images/WhatsApp Image 2026-02-25 at 08.05.40.jpeg'],
            'leenauto.image_2'    => ['type' => 'image',    'label' => 'Foto 2 (thumbnail)',     'default' => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (1).jpeg'],
            'leenauto.image_3'    => ['type' => 'image',    'label' => 'Foto 3 (thumbnail)',     'default' => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (2).jpeg'],
            'leenauto.image_4'    => ['type' => 'image',    'label' => 'Foto 4 (thumbnail)',     'default' => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (3).jpeg'],
            'leenauto.image_5'    => ['type' => 'image',    'label' => 'Foto 5 (thumbnail)',     'default' => 'images/WhatsApp Image 2026-02-25 at 08.05.41 (4).jpeg'],
            'leenauto.cta_primary'   => ['type' => 'text',  'label' => 'Primaire knop tekst',    'default' => 'Reserveer nu'],
            'leenauto.cta_secondary' => ['type' => 'text',  'label' => 'Secundaire knop tekst',  'default' => 'Bel direct'],
        ],
    ],

    // ====================================================================
    // 06. Werkplaats CTA
    // ====================================================================
    'werkplaats' => [
        'label' => 'Werkplaats blok',
        'icon'  => '🔧',
        'fields' => [
            'werkplaats.eyebrow' => ['type' => 'text',     'label' => 'Eyebrow',          'default' => 'Werkplaats'],
            'werkplaats.title'   => ['type' => 'text',     'label' => 'Titel (1e regel)', 'default' => 'APK, beurt of reparatie?'],
            'werkplaats.title2'  => ['type' => 'text',     'label' => 'Titel (2e regel)', 'default' => 'Vul je kenteken, wij doen de rest.'],
            'werkplaats.image'   => ['type' => 'image',    'label' => 'Achtergrondfoto',  'default' => 'images/afspraak-banner.jpg'],
        ],
    ],

];
