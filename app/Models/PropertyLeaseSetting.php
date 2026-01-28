<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLeaseSetting extends Model
{
    protected $fillable = [
        'property_id',
        'require_signature',
        'require_manual_approval',
        'auto_activate_on_sign',
        'allow_multiple_active_leases',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}

