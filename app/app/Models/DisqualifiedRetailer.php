<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisqualifiedRetailer extends Model
{
    protected $fillable = [
        'store_name',
        'street_address',
        'borough',
        'state',
        'zip_code',
        'case_type',
        'fad_date',
        'case_number',
        'outcome',
    ];

    protected $casts = [];
