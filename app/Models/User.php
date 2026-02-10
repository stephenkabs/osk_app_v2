<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'property_id',
        'slug',
        'status',
        'special_code',
        'whatsapp_line',
        'whatsapp_phone',
        'address',
        'occupation',
        'type',
        'profile_image',
        'kyc_status',
        'quickbooks_customer_id',
           'arrived_date',
    'leave_date',

    ];

    /**
     * Hidden attributes (not serialized)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Boot method to auto-set slug and special_code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $baseSlug = Str::slug($user->name);
            $slug = $baseSlug;
            $count = 1;

            while (self::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $user->slug = $slug;
            $user->special_code = 'TEN' . strtoupper(Str::random(6));
        });
    }

    /**
     * ======================
     * Relationships
     * ======================
     */

    // 🔗 Each user belongs to one property


        public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}



    public function merchants()
{
    return $this->hasMany(Merchant::class);
}


public function properties()
{
    return $this->belongsToMany(Property::class);
}

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

  public function leaseAgreements()
    {

        return $this->hasMany(\App\Models\PropertyLeaseAgreement::class, 'user_id');
    }


        public function agreements()
    {
        return $this->hasMany(PropertyLeaseAgreement::class);
    }
public function unit()
{
    return $this->belongsTo(\App\Models\Unit::class);
}


public function leases()
{
    return $this->hasMany(PropertyLeaseAgreement::class, 'user_id');
}



    /**
     * ======================
     * Helper methods
     * ======================
     */

    public function isActive(): bool
    {
        return $this->status == 1;
    }

    public function hasProfileImage(): bool
    {
        return !empty($this->profile_image);
    }

    // App\Models\User.php
public function partner()
{
    return $this->hasOne(\App\Models\Partner::class);
}

}
