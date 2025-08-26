<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_day_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('booking_day_id')->constrained()->cascadeOnDelete();
            $t->foreignId('itinerary_item_id')->nullable()->constrained()->nullOnDelete(); // template link
            $t->unsignedInteger('position')->default(0);
            $t->string('title');
            $t->text('description')->nullable();
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->string('location')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_day_items');
    }
};
