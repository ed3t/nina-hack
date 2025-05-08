<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email'];

    /**
     * Get the address associated with the user.
     */
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}

