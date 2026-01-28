<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaseTemplate extends Model
{
    protected $fillable = [
        'property_id',
        'title',
        'terms',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
