<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressChurn extends Model
{
    protected $table = 'address_churn';

    protected $fillable = [
        'street_address',
        'zip_code',
        'store_types',
        'total_auth_count',
        'deauth_count',
        'address_history_years',
        'churn_tier',
        'store_names',
    ];

    protected $casts = [
        'total_auth_count' => 'integer',
        'deauth_count' => 'integer',
        'address_history_years' => 'decimal:1',
    ];
}
