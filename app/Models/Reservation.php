<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    protected $fillable = [
        'resource_type',   // 'aanhanger' | 'stofzuiger'
        'start_at',
        'end_at',
        'reserved_by',
        'phone',
        'email',
        'status',          // 'confirmed' | 'pending' | 'cancelled'
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    // Filter op type
    public function scopeOfType($query, string $type)
    {
        return $query->where('resource_type', $type);
    }

    /**
     * Overlap-check: (existing.end > new.start) && (existing.start < new.end)
     * 'cancelled' telt niet mee.
     */
    public static function overlaps(string $type, $start, $end, ?int $ignoreId = null): bool
    {
        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end   = $end   instanceof Carbon ? $end   : Carbon::parse($end);

        return static::where('resource_type', $type)
            ->where('status', '!=', 'cancelled')
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('end_at',   '>', $start)
            ->where('start_at', '<',  $end)
            ->exists();
    }
}
?>