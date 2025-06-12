<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    /** @use HasFactory<\Database\Factories\FlightFactory> */
    use HasFactory;
    
    protected $fillable = [
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'price',
    ];

    // Relationships with airports
    public function originAirport()
    {
        return $this->belongsTo(Airport::class, 'origin', 'code');
    }

    public function destinationAirport()
    {
        return $this->belongsTo(Airport::class, 'destination', 'code');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
