<?php

use App\Models\SiteSetting;

if (! function_exists('setting')) {
    /**
     * Lees een site-instelling. Valt terug op de default uit
     * config/site_content.php als de DB-waarde leeg is.
     *
     *   {{ setting('hero.title_line1') }}
     *   {{ setting('contact.phone_sales', '06 38 25 79 87') }}
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}

if (! function_exists('setting_image')) {
    /**
     * Lees een afbeelding-instelling als full asset URL.
     * Werkt zowel voor relative paden uit /public/images/ als
     * voor uploads in storage/app/public/.
     */
    function setting_image(string $key, ?string $default = null): string
    {
        $value = SiteSetting::get($key, $default) ?? '';
        if ($value === '') return $default ? asset($default) : '';

        // Storage upload (begint met "site/" of "uploads/")
        if (str_starts_with($value, 'site/') || str_starts_with($value, 'uploads/')) {
            return asset('storage/' . $value);
        }
        // Bestand in /public (default fallbacks)
        return asset($value);
    }
}

if (! function_exists('setting_tel')) {
    /**
     * Format een telefoonnummer voor `tel:` href (alleen + en cijfers).
     */
    function setting_tel(string $key): string
    {
        $raw = SiteSetting::get($key, '') ?? '';
        $clean = preg_replace('/[^\d+]/', '', $raw);
        if ($clean === '') return '';
        // Nederlandse 06-nummers prefixen we met +31
        if (str_starts_with($clean, '06')) {
            $clean = '+31' . substr($clean, 1);
        }
        return $clean;
    }
}

if (! function_exists('setting_phone')) {
    /**
     * Display-format voor telefoonnummers: "06 38 25 79 87".
     * Werkt ongeacht hoe de admin het nummer invoert (met of zonder spaties,
     * met of zonder landcode).
     */
    function setting_phone(string $key): string
    {
        $raw = SiteSetting::get($key, '') ?? '';
        $clean = preg_replace('/[^\d]/', '', $raw);
        if ($clean === '') return '';

        // +31 6... → 06...
        if (str_starts_with($clean, '316')) {
            $clean = '0' . substr($clean, 2);
        }

        // Nederlandse mobiel: 06 + 4 paren van 2 cijfers (10 cijfers totaal)
        if (str_starts_with($clean, '06') && strlen($clean) === 10) {
            return '06 ' . substr($clean, 2, 2) . ' ' . substr($clean, 4, 2)
                 . ' ' . substr($clean, 6, 2) . ' ' . substr($clean, 8, 2);
        }

        // Vaste nummers: 0 + areacode + rest, met spatie na areacode (3 of 4 cijfers)
        if (str_starts_with($clean, '0') && strlen($clean) >= 9) {
            return substr($clean, 0, 3) . ' ' . substr($clean, 3);
        }

        return $raw;
    }
}
