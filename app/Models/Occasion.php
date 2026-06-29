<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Occasion extends Model
{
    protected $fillable = [
    'hexon_nr',
    'merk','model','type','transmissie','brandstof','kenteken', 'kleur', 'interieurkleur','btw_marge',
    'cilinderinhoud','carrosserie','max_trekgewicht','apk_tot','energielabel','wegenbelasting_min',
    'aantal_deuren','tellerstand','bouwjaar','prijs','bekleding','aantal_cilinders','topsnelheid',
    'gewicht','laadvermogen','bijtelling','gemiddeld_verbruik','hoofdfoto_path','galerij','slug',
    'vermogen_pk',
    'exterieur_options','interieur_options','veiligheid_options','overige_options','omschrijving',
    'binnenkort','verwachte_prijs','oude_prijs',
    'inkoop_prijs',
    'verkocht_datum','verkoopprijs','verkocht_aan',
];


protected $casts = [
    'apk_tot' => 'date',
    'galerij' => 'array',
    'exterieur_options' => 'array',
    'interieur_options' => 'array',
    'veiligheid_options' => 'array',
    'overige_options' => 'array',
    'binnenkort' => 'boolean',
    'verwachte_prijs' => 'decimal:2',
    'oude_prijs' => 'decimal:2',
    'inkoop_prijs' => 'decimal:2',
    'verkocht_datum' => 'date',
    'verkoopprijs' => 'decimal:2',
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

    /** Marge in euro's (verkoopprijs - inkoopprijs). Null als één van beide ontbreekt. */
    public function getMargeAttribute(): ?float
    {
        if ($this->inkoop_prijs === null || $this->prijs === null) return null;
        return (float) $this->prijs - (float) $this->inkoop_prijs;
    }

    /** Marge als percentage van inkoopprijs. */
    public function getMargePercentAttribute(): ?float
    {
        $marge = $this->marge;
        if ($marge === null || (float) $this->inkoop_prijs <= 0) return null;
        return round(($marge / (float) $this->inkoop_prijs) * 100, 1);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(OccasionNote::class)->latest();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderByRaw('CASE WHEN completed_at IS NULL THEN 0 ELSE 1 END')->orderBy('due_at');
    }

    /** Is deze auto verkocht? Check beide bronnen — nieuwe DB-veld én legacy "(VERKOCHT)" marker. */
    public function getIsSoldAttribute(): bool
    {
        if ($this->verkocht_datum !== null) return true;
        return stripos($this->model ?? '', '(VERKOCHT)') !== false;
    }

    /** Gerealiseerde marge (verkoopprijs - inkoopprijs). Null als één ontbreekt. */
    public function getGerealiseerdeMargeAttribute(): ?float
    {
        if ($this->verkoopprijs === null || $this->inkoop_prijs === null) return null;
        return (float) $this->verkoopprijs - (float) $this->inkoop_prijs;
    }

    /** Hoeveel dagen stond de auto in voorraad bij verkoop? */
    public function getDagenInVoorraadAttribute(): ?int
    {
        if ($this->verkocht_datum === null || $this->created_at === null) return null;
        return (int) $this->created_at->diffInDays($this->verkocht_datum);
    }

    /** Scope: alleen verkochte auto's (op basis van DB-veld). */
    public function scopeSold($query)
    {
        return $query->whereNotNull('verkocht_datum');
    }
}
