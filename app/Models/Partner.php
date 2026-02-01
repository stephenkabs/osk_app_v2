<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'nrc_no',
        'phone_number',
        'previous_address',
        'nrc_image',
        'email',
        'slug',
        'user_id',
            'status',   // ✅ add this
                   'quickbooks_customer_id', // ✅ new
        'agreement_accepted',
        'agreement_accepted_at',
        'agreement_version',
        'agreement_text',
        'agreement_signature',
        'agreement_ip',
        'agreement_user_agent',
    ];

    protected $casts = [
        'agreement_accepted' => 'boolean',
        'agreement_accepted_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }


//     public function invests()
// {
//     return $this->hasMany(Invest::class);
// }

public function payments()
{
    return $this->hasMany(\App\Models\Payment::class);
}

public function availableBalance()
{
    $totalRevenue = $this->revenues()->where('status','approved')->sum('revenue_amount');
    $totalWithdrawn = $this->withdraws()->whereIn('status', ['pending','approved'])->sum('amount');
    return max($totalRevenue - $totalWithdrawn, 0);
}


// public function revenues()
// {
//     return $this->hasMany(\App\Models\Revenue::class);
// }

// public function withdraws()
// {
//     return $this->hasMany(\App\Models\Withdraw::class);
// }

// public function accounts()
// {
//     return $this->hasMany(\App\Models\Account::class, 'user_id', 'user_id');
// }



}
