<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    use HasFactory;
    
    public function comments(): HasMany
    {
        return $this->hasMany(Transaction::class)->chaperone();
    }
    protected $fillable = [
        'commodity_name',
    ];
    protected $primaryKey = 'commodity_id';
}
