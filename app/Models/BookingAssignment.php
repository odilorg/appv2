<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAssignment extends Model
{
   protected $fillable = [
        'booking_day_id','role','assignable_type','assignable_id',
        'start_time','end_time','qty','price','currency','is_confirmed','notes',
    ];
    protected $casts = ['is_confirmed'=>'boolean','qty'=>'integer','price'=>'decimal:2'];

    public function day() { return $this->belongsTo(BookingDay::class, 'booking_day_id'); }
    public function assignable() { return $this->morphTo(); } // Guide, Driver, Vehicle, etc.
}
