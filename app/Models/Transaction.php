<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'msisdn',
        'amount',
        'gateway',
        'reference',
        'status',
        'wirepick_gw_id',
        'mno_request_id',
        'mno_fintxn_id',
        'raw_request',
            'gw_id',
        'raw_response',
        'callback_url',
    ];

    protected $casts = [
        'raw_request'  => 'array',
        'raw_response' => 'array',
    ];
}
