<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Investment.php
class Investment extends Model
{
    protected $fillable = [
        'user_id','property_id','shares','price_per_share','total_amount',
        'status','qbo_sync_status','qbo_sync_error','confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function investor() { return $this->belongsTo(User::class, 'user_id'); }
    public function property() { return $this->belongsTo(Property::class); }
    public function payments() { return $this->hasMany(InvestmentPayment::class); }

    public function successfulPayment()
    {
        return $this->hasOne(InvestmentPayment::class)->where('gateway_status','success');
    }
}
