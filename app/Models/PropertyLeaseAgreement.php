<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PropertyLeaseAgreement extends Model
{
    use HasFactory;

protected $fillable = [
  'property_id','unit_id','user_id','slug',
  'start_date','end_date','payment_day',
  'rent_amount','deposit_amount','utilities_cap',
  'bank_name','account_name','account_number',
  'landlord_name','landlord_email','tenant_email','tenant_id_no',
  'reference','lease_number','status','signed_at','notes',
  'tenant_signature_path',   // <— add
];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->slug)) {
                $base = Str::slug(($m->lease_number ?: 'lease-').Str::random(6));
                while (self::where('slug', $base)->exists()) {
                    $base = Str::slug(($m->lease_number ?: 'lease-').Str::random(6));
                }
                $m->slug = $base;
            }
        });
    }

    public function property()  { return $this->belongsTo(Property::class); }
    public function unit()      { return $this->belongsTo(Unit::class, 'unit_id'); }
    public function tenant()    { return $this->belongsTo(User::class, 'user_id'); }
    public function payments()
{
    return $this->hasMany(PropertyPayment::class, 'lease_agreement_id');
}


public function user()
{
    return $this->belongsTo(User::class);
}




}
