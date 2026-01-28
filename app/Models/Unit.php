<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'code',
        'slug',
        'type',
        'floor',
        'bedrooms',
        'bathrooms',
        'rent_amount',
        'deposit_amount',
        'size_sq_m',
        'status',
        'photo_path',
        'notes',
    ];

    // Use slug for route-model binding if you prefer {unit:slug}
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function (Unit $unit) {
            if (empty($unit->slug)) {
                $base = Str::slug($unit->code);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $unit->slug = $slug;
            }
        });
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }


    public function users()
{
    return $this->hasMany(\App\Models\User::class);
}


    // Future:
    // public function leases() { return $this->hasMany(Lease::class); }
}
