<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
    use App\Models\Pay;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

protected $fillable = [
    'property_name',
    'address',
    'location',
    'lat',
    'lng',
    'radius_m',
    'user_id',
    'slug',
    'unit_id',
    'total_shares',
    'bidding_price',
'images',  // JSON array of image paths
    // ✅ QuickBooks Sync Fields
    'qbo_item_id',
    'qbo_qty_on_hand',
    'qbo_unit_price',
    'qbo_synced_at',
];




protected $casts = [
    'lat'             => 'float',
    'lng'             => 'float',
    'radius_m'        => 'integer',
    'total_shares'    => 'integer',
    'bidding_price'   => 'decimal:2',
    'qbo_qty_on_hand' => 'integer',
    'qbo_unit_price'  => 'decimal:2',
    'images' => 'array', // ensures JSON is cast to array automatically

    'qbo_synced_at'   => 'datetime',
];


    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Accessor to calculate price per share dynamically.
     * Example: $property->price_per_share
     */
    public function getPricePerShareAttribute(): float
    {
        return ($this->total_shares > 0)
            ? round($this->bidding_price / $this->total_shares, 2)
            : 0.0;
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            // Auto-attach creator if not provided
            if (blank($property->user_id) && auth()->check()) {
                $property->user_id = auth()->id();
            }

            // Generate unique slug if missing
            if (blank($property->slug)) {
                $base = Str::slug($property->property_name);
                $slug = $base;
                $i = 1;
                while (self::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $property->slug = $slug;
            }
        });

        static::updating(function (Property $property) {
            // If name changed and slug not explicitly set, update slug (optional)
            if ($property->isDirty('property_name') && $property->getOriginal('property_name') !== $property->property_name) {
                if ($property->isDirty('slug') === false) {
                    $base = Str::slug($property->property_name);
                    $slug = $base;
                    $i = 1;
                    while (self::where('slug', $slug)->where('id', '!=', $property->id)->exists()) {
                        $slug = $base.'-'.$i++;
                    }
                    $property->slug = $slug;
                }
            }
        });
    }
    protected $appends = ['remaining_shares']; // ✅ force accessor to be available in Blade/JSON

public function getRemainingSharesAttribute(): int
{
    $usedShares = $this->divisions()->sum('total_shares');
    return max(($this->qbo_qty_on_hand ?? 0) - $usedShares, 0);
}


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

        public function units()
{
    return $this->hasMany(\App\Models\Unit::class);
}

    // public function divisions()
    // {
    //     return $this->hasMany(\App\Models\Division::class);
    // }

    public function users()
{
    return $this->hasMany(\App\Models\User::class);
}

   // 👇 Add this
    public function agreements()
    {
        return $this->hasMany(\App\Models\PropertyLeaseAgreement::class);
    }


public function leaseTemplate()
{
    return $this->hasOne(LeaseTemplate::class);
}


public function investments()
{
    return $this->hasMany(\App\Models\Investment::class);
}

public function confirmedShares(): int
{
    return (int) $this->investments()
        ->where('status', 'confirmed')
        ->sum('shares');
}

public function availableShares(): int
{
    return max(0, (int)$this->qbo_qty_on_hand - $this->confirmedShares());
}




// 👇 ADD THIS
// public function payments()
// {
//     return $this->hasManyThrough(Pay::class, Unit::class);
// }
// public function unitPayments()
// {
//     return $this->hasMany(UnitPayment::class);
// }

// public function rentPayments()
// {
//     return $this->hasMany(RentPayment::class);
// }


}
