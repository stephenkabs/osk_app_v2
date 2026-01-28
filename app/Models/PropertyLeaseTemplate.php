<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLeaseTemplate extends Model
{
    protected $fillable = [
        'parties_clause',
        'property_clause',
        'term_clause',
        'rent_clause',
        'deposit_clause',
        'maintenance_clause',
        'termination_clause',
        'additional_terms',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
