<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyExpense extends Model
{
    protected $fillable = [
        'property_id',
        'unit_id',
        'category',
        'title',
        'description',
        'amount',
        'expense_date',
        'reference',
        'payment_method',
        'recorded_by',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
