<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Vehicle extends Model
{
    use HasFactory;

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'vehicle_id', 'staff_id');
    }
    public function vehicle_types(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }
    // public function locations(): BelongsToMany
    // {
    //     return $this->belongsToMany(Location::class, 'location_vehicles')
    //                 ->using(LocationVehicle::class)
    //                 ->withTimestamps(); 
    // }  
    public function location(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'facilitator_location_vehicles')
                    ->using(FacilitatorLocationVehicle::class)
                    ->withTimestamps(); 
    }
    public function facilitator(): BelongsToMany
    {
        return $this->belongsToMany(Facilitator::class, 'facilitator_location_vehicles')
                    ->using(FacilitatorLocationVehicle::class)
                    ->withTimestamps(); 
    }
    protected $fillable = [
        'plate_number',
        'vehicle_name',
        'vehicle_type_id',
    ];
    protected $primaryKey = 'vehicle_id';
}
