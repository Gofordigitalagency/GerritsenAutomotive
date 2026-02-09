<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclameItem extends Model
{
    protected $fillable = ['reclame_id','occasion_id','position'];

    public function reclame()
    {
        return $this->belongsTo(Reclame::class);
    }

    public function occasion()
    {
        return $this->belongsTo(Occasion::class);
    }
}
