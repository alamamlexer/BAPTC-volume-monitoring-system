<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Facilitator extends Model
{
    use HasFactory;
    protected $primaryKey = 'facilitator_id';
    
    protected $fillable = [
        'facilitator_code',
        'facilitator_name',
    ];
   
    
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'facilitator_id', 'facilitator_id');
    }
    
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'facilitator_location_vehicles')
                    ->using(FacilitatorLocationVehicle::class)
                    ->withTimestamps(); 
    }
    public function location(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'facilitator_location_vehicles')
                    ->using(FacilitatorLocationVehicle::class)
                    ->withTimestamps(); 
    }
}
