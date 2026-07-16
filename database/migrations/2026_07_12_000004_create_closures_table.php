<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closures', function (Blueprint $table) {
            $table->id();
            // Exactly one of these is set:
            //  - `date` → the studio is closed on this specific date (a holiday).
            //  - `weekday` → the studio is closed every week on this ISO weekday
            //    (1 = Mon … 7 = Sun) — a recurring day off.
            $table->date('date')->nullable()->unique();
            $table->unsignedTinyInteger('weekday')->nullable()->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closures');
    }
};
