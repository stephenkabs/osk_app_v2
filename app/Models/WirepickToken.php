<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WirepickToken extends Model
{
    protected $fillable = [
        'access_token',
        'expires_at'
    ];

    public $timestamps = true;
}
