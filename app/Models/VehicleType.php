<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    use HasFactory;
    public function comments(): HasMany
    {
        return $this->hasMany(Vehicle::class)->chaperone();
    }
   
    protected $primaryKey = 'vehicle_type_id';
}
