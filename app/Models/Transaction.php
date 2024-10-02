<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    
    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function commodities(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    protected $fillable = [
        'date',
        'time',
        'transaction_type',
        'transaction_status',
        'staff_id',
        'commodity_id',
        'volume',
        'plate_number',
        'vehicle_type_id',
        'name',
        'barangay',
        'municipality',
        'province',
        'region',
    ];

}
