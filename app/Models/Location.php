<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'barangay',
        'municipality',
        'province',
        'region',
    ];
    protected $primaryKey = 'location_id';
    

    // public function vehicles(): BelongsToMany
    // {
    //     return $this->belongsToMany(Vehicle::class, 'location_vehicles')
    //                 ->using(LocationVehicle::class)
    //                 ->withTimestamps(); 
    // }
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'facilitator_location_vehicles')
                    ->using(FacilitatorLocationVehicle::class)
                    ->withTimestamps(); 
    }
    public function facilitator(): BelongsToMany
    {
        return $this->belongsToMany(Facilitator::class, 'facilitator_location_vehicles')
                    ->using(FacilitatorLocationVehicle::class)
                    ->withTimestamps(); 
    }
    

}
