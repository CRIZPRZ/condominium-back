<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function scopeOfProperty($query, $propertyId)
    {
        if ($propertyId !== null) {
            $query->where('property_id', $propertyId);
        }
        return $query->orderBy('id', 'DESC');
    }


    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

}
