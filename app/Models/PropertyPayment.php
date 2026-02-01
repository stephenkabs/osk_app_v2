<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'lease_agreement_id',
        'user_id',
        'payment_date',
        'payment_month',
        'amount',
        'method',
        'reference',
        'status',

        // 🔹 Gateway fields
        'gateway',
        'gateway_reference',
        'gateway_status',
        'gateway_payload',

        'recorded_by',
    ];

    protected $casts = [
        'payment_date'    => 'date',
        'amount'          => 'decimal:2',
        'gateway_payload' => 'array',
    ];

    /* ============================
       RELATIONSHIPS
    ============================ */

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function lease()
    {
        return $this->belongsTo(PropertyLeaseAgreement::class, 'lease_agreement_id');
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /* ============================
       PAYMENT STATE HELPERS
    ============================ */

    public function isPaid(): bool
    {
        return $this->gateway_status === 'success'
            || $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->gateway_status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->gateway_status === 'failed';
    }

    /* ============================
       SCOPES (VERY USEFUL)
    ============================ */

    public function scopeGateway($query, string $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    public function scopePending($query)
    {
        return $query->where('gateway_status', 'pending');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('gateway_status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('gateway_status', 'failed');
    }
}
