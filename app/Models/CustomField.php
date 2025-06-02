<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module',
        'label',
        'name',
        'type',
        'options',
        'is_required',
        'is_visible_in_table',
        'order',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options'             => 'array',
        'is_required'         => 'boolean',
        'is_visible_in_table' => 'boolean',
        'order'               => 'integer',
    ];
}
