<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDay extends Model
{
    protected $fillable = ['booking_id','day_number','date'];
    protected $casts = ['date' => 'date','day_number'=>'integer'];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function items() { return $this->hasMany(BookingDayItem::class)->orderBy('position'); }
    public function assignments() { return $this->hasMany(BookingAssignment::class); }
}
