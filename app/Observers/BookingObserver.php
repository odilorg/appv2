<?php
// app/Observers/BookingObserver.php
namespace App\Observers;

use App\Models\Booking;
use Illuminate\Support\Carbon;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        $duration = (int) ($booking->tour->tour_duration ?? 1);

        for ($i = 1; $i <= max(1, $duration); $i++) {
            $day = $booking->days()->create([
                'day_number' => $i,
                'date' => Carbon::parse($booking->start_date)->addDays($i - 1),
            ]);

            $items = $booking->tour->itineraryItems()
                ->where(function ($q) use ($i) {
                    $q->whereNull('day_number')->orWhere('day_number', $i);
                })
                ->orderBy('position')
                ->get();

            foreach ($items as $idx => $it) {
                $day->items()->create([
                    'itinerary_item_id' => $it->id,
                    'position' => $idx + 1,
                    'title' => $it->title,
                    'description' => $it->description,
                    'start_time' => $it->start_time,
                    'end_time' => $it->end_time,
                    'location' => $it->location,
                ]);
            }
        }
    }
}
