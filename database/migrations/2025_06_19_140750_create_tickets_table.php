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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            //event_id - foreign key to events table
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            //sku_id - foreign key to skus table
            $table->foreignId('sku_id')->constrained()->onDelete('cascade');
            //ticket_code
            $table->string('ticket_code');
            //ticket_date nullable
            $table->date('ticket_date')->nullable();
            //status default available
            $table->enum('status', ['available', 'sold', 'booked', 'redeem'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
