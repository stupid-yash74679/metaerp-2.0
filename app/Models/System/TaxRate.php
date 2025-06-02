<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TaxRate extends Model
{
    use HasFactory;

    protected $table = 'tax_rates';

    protected $fillable = [
        'name',
        'rate_percentage',
        'tax_type',
        'compound_tax',
        'collective_tax',
        'components',
        'region',
        'is_default',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'rate_percentage' => 'decimal:4',
        'components' => 'array',
        'compound_tax' => 'boolean',
        'collective_tax' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
