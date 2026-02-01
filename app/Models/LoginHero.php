<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHero extends Model
{
    protected $fillable = [
        'image',
        'title',
        'text',
        'tagline',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
