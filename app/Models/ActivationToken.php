<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivationToken extends Model
{
    protected $table = 'activation_tokens'; // This tells Laravel which table to use
    
    protected $fillable = [
        'email',
        'token',
    ];
    
    // Optionally, you can define timestamps if you want automatic created_at/updated_at fields
    public $timestamps = true;
}