<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/InvestmentPayment.php
class InvestmentPayment extends Model
{
    protected $fillable = [
        'investment_id','user_id','amount','currency','method',
        'gateway','gateway_reference','gateway_status','gateway_payload'
    ];

    protected $casts = [
        'gateway_payload' => 'array'
    ];

    public function investment() { return $this->belongsTo(Investment::class); }
    public function user() { return $this->belongsTo(User::class); }
}
