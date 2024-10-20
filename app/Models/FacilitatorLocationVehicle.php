<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FacilitatorLocationVehicle extends Pivot
{
    use HasFactory;
    public $incrementing = true;
    protected $table = 'facilitator_location_vehicles';
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id'); 
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id'); 
    }
    public function facilitator()
    {
        return $this->belongsTo(Facilitator::class, 'facilitator_id'); 
    }
}
