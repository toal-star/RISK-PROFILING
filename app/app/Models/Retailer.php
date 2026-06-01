<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    protected $fillable = [
        'fns_record_id',
        'store_name',
        'store_type',
        'street_address',
        'city',
        'borough',
        'zip_code',
        'county',
        'state',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];
}
