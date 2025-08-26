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
       Schema::create('booking_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('booking_day_id')->constrained()->cascadeOnDelete();
            $t->string('role'); // 'guide','driver','vehicle','hotel','restaurant', ...
            $t->morphs('assignable'); // assignable_type, assignable_id
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->unsignedInteger('qty')->default(1); // nights, hours, etc.
            $t->decimal('price', 12, 2)->nullable();
            $t->string('currency', 3)->default('USD');
            $t->boolean('is_confirmed')->default(false);
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_assignments');
    }
};
