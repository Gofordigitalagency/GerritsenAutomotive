<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Occasion extends Model
{
    protected $fillable = [
    'merk','model','type','transmissie','brandstof','kenteken', 'kleur', 'interieurkleur','btw_marge',
    'cilinderinhoud','carrosserie','max_trekgewicht','apk_tot','energielabel','wegenbelasting_min',
    'aantal_deuren','tellerstand','bouwjaar','prijs','bekleding','aantal_cilinders','topsnelheid',
    'gewicht','laadvermogen','bijtelling','gemiddeld_verbruik','hoofdfoto_path','galerij','slug',
    'vermogen_pk',
    // nieuw:
    'exterieur_options','interieur_options','veiligheid_options','overige_options','omschrijving',
];


protected $casts = [
    'apk_tot' => 'date',
    'galerij' => 'array',
    // nieuw:
    'exterieur_options' => 'array',
    'interieur_options' => 'array',
    'veiligheid_options' => 'array',
    'overige_options' => 'array',
];

    protected static function booted()
    {
        static::creating(function ($o) {
            if (empty($o->slug)) {
                $o->slug = Str::slug($o->merk.' '.$o->model.' '.$o->type.' '.$o->bouwjaar.' '.Str::random(4));
            }
        });
    }

    public function getTitelAttribute()
    {
        return trim("{$this->merk} {$this->model}".($this->type ? " {$this->type}" : ""));
    }
}
