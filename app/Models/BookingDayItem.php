<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDayItem extends Model
{
   protected $fillable = [
        'booking_day_id','itinerary_item_id','position','title','description',
        'start_time','end_time','location',
    ];

    public function day() { return $this->belongsTo(BookingDay::class, 'booking_day_id'); }
    public function itineraryItem() { return $this->belongsTo(ItineraryItem::class); }
}
