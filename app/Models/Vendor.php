<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'notes',
        'rating',
        'is_active',
        'tax_id',
        'website',
        'payment_terms',
        'account_number',
        'bank_name',
        'bank_branch',
        'bank_address',
        'swift_code',
        'iban',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
