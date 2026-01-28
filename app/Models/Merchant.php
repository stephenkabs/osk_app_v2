<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Merchant extends Model
{
    use HasFactory;

      protected $fillable = [
        'user_id',
        'name',
        'api_key',
        'callback_url',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
