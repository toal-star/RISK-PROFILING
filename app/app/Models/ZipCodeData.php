<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZipCodeData extends Model
{
    protected $primaryKey = 'zip_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'zip_code',
        'borough',
        'population',
        'median_household_income',
        'income_bracket',
        'pct_below_poverty',
        'poverty_tier',
    ];

    protected $casts = [
        'population' => 'integer',
        'median_household_income' => 'decimal:2',
        'pct_below_poverty' => 'integer',
    ];

    public function retailers(): HasMany
    {
        return $this->hasMany(Retailer::class, 'zip_code', 'zip_code');
    }
}
