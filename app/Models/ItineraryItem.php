<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryItem extends Model
{
   protected $fillable = [
       'tour_id',
       'day_number',
       'position',
       'title',
       'description',
       'start_time',
       'end_time',
       'location',
   ];

   public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
