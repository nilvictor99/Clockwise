<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'phoneable_id',
        'phoneable_type',
        'phone_type',
        'phone_number',
        'country_code',
        'verified_at',
    ];

    public function scopeSearchNumber(Builder $query, $search)
    {
        return $query->whereRaw('phone_number ILIKE ?', ["%{$search}%"]);
    }
}
