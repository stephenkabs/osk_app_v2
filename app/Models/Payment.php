<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'division_id',
         'property_id',
        'method',
        'status',
        'reference',
        'stripe_session_id',
        'amount',
        'currency',
        'quickbooks_sales_receipt_id',
        'quickbooks_customer_id',
        'quickbooks_link',
        'my_total_shares', // ✅ new fillable field
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'my_total_shares' => 'decimal:4',
    ];

    /* Relationships */
    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class);
    }

    // App\Models\Payment.php
public function property()
{
    return $this->belongsTo(Property::class);
}

}
