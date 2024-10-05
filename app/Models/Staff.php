<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory;
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
    
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'attendant_id', 'staff_id');
    }
    protected $fillable = [
        'staff_name',
        'email',
        'contact_number',
        'password',
    ];
    protected $primaryKey = 'staff_id';
    protected $table = 'staff';
}
