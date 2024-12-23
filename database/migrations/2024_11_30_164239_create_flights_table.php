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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('origin');
            $table->string('destination');
            $table->foreign('origin')->references('code')->on('airports')->onDelete('cascade');
            $table->foreign('destination')->references('code')->on('airports')->onDelete('cascade');
            $table->datetime('departure_time');
            $table->datetime('arrival_time');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
