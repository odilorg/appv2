<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\BookingDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'tour_id','customer_id','start_date','pax_adults','pax_children',
        'status','selling_price','currency','notes',
    ];
    protected $casts = [
        'start_date' => 'date',
        'pax_adults' => 'integer',
        'pax_children' => 'integer',
        'selling_price' => 'decimal:2',
    ];

    public function tour() { return $this->belongsTo(Tour::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function days(): HasMany { return $this->hasMany(BookingDay::class)->orderBy('day_number'); }
}
