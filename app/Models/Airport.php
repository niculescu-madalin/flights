<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'city',
        'country',
    ];

    public function originFlights() {
        return $this->hasMany(Flight::class, 'origin', 'code');
    }

    public function destinationFlight() {
        return $this->hasMany(Flight::class, 'destination', 'code');
    }
}
