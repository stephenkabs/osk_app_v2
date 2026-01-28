<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPayment extends Model
{
    protected $fillable = [
        'property_id',
    'lease_agreement_id', // ✅ MUST BE HERE
        'user_id',
        'payment_date',
        'payment_month',
        'amount',
        'method',
        'reference',
        'status',
        'recorded_by',
    ];

    public function lease()
    {
        return $this->belongsTo(PropertyLeaseAgreement::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}



