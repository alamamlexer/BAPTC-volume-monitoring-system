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
    
    public function comments(): HasMany
    {
        return $this->hasMany(Transaction::class)->chaperone();
    }
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class);
    }
    

}
