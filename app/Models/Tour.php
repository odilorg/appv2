<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
       protected $fillable = ['title', 'tour_duration', 'tour_description'];

       public function itineraryItems()
    {
        return $this->hasMany(ItineraryItem::class)->orderBy('day_number')->orderBy('position');
    }

}
