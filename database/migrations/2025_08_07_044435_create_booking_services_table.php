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
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('service_name'); // e.g., "Laundry Kemeja", "Extra Bed", "Makan Malam"
            $table->decimal('price', 15, 2); // Harga layanan tersebut
            $table->integer('quantity')->default(1); // Jumlah layanan, misal laundry 3 potong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
