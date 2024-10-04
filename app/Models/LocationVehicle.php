<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LocationVehicle extends Pivot
{
    use HasFactory;
        /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    protected $table = 'location_vehicles';
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id'); // Adjust if needed
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id'); // Adjust if needed
    }
    
}
