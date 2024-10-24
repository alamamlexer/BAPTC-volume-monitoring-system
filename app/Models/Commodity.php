<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    use HasFactory;
    
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'commodity_id', 'commodity_id');
    }
    protected $fillable = [
        'commodity_name',
    ];
    protected $primaryKey = 'commodity_id';
}
