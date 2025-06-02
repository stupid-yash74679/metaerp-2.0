<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tds extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'rate',
        'threshold_limit',
        'description',
    ];
}
