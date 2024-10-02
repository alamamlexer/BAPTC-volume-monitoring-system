<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outflow extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'time',
        'transaction_status',
        'attendant',
        'plate_number',
        'vehicle_type',
        'name',
        'commodity',
        'volume',
        'barangay',
        'municipality',
        'province',
        'region',
    ];
}
