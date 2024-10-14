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
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class, 'commodity_id');
    }
    public function vehicle_type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function getAddressAttribute()
{
    return $this->barangay . ', ' . $this->municipality . ', ' . $this->province . ', ' . $this->region;
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