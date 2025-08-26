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
         Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $t->foreignId('customer_id')->nullable()->constrained()->nullOnDelete(); // you said you added customers
            $t->date('start_date');
            $t->unsignedInteger('pax_adults')->default(2);
            $t->unsignedInteger('pax_children')->default(0);
            $t->string('status')->default('draft'); // draft|confirmed|cancelled
            $t->decimal('selling_price', 12, 2)->nullable();
            $t->string('currency', 3)->default('USD');
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
