<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclame extends Model
{
    protected $fillable = ['title','subtitle','valid_from','valid_to'];

    public function items()
    {
        return $this->hasMany(ReclameItem::class)->orderBy('position');
    }
}
