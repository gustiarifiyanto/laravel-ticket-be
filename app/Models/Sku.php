<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $fillable = [
        'name',
        'category',
        'event_id', // Assuming Sku belongs to an Event
        'price', // Assuming Sku has a price
        'stock', // Assuming Sku has a stock quantity
        'day_type', // Assuming Sku has a day type
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    //Ticketing related methods can be added here
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
