<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    /**
     * Mendapatkan data tamu yang melakukan booking ini.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Mendapatkan semua layanan tambahan untuk booking ini.
     */
    public function services(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'booking_room')
            ->withPivot('price_at_booking')
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    public function getGrandTotalAttribute()
    {
        // 1. Hitung total harga kamar dari data relasi
        $checkIn = Carbon::parse($this->check_in_date)->startOfDay();
        $checkOut = Carbon::parse($this->check_out_date)->startOfDay();
        $nights = $checkIn->diffInDays($checkOut) ?: 1;

        $roomTotal = $this->rooms->sum(function ($room) use ($nights) {
            return $room->pivot->price_at_booking * $nights;
        });

        // 2. Hitung total layanan. total_amount berisi (kamar + layanan),
        //    jadi kita bisa kurangi untuk mendapatkan total layanan saja.
        $serviceTotal = $this->total_amount - $roomTotal;

        // 3. Dasar Pengenaan Pajak & Diskon HANYA dari total kamar
        $taxableAmount = $roomTotal - $this->discount;

        // 4. Hitung jumlah PPN
        $taxAmount = $taxableAmount * ($this->tax_percentage / 100);

        // 5. Grand Total = (Kamar - Diskon) + PPN + Total Layanan
        return $taxableAmount + $taxAmount + $serviceTotal;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // Log hanya akan dibuat jika nilai kolom ini berubah
            ->logOnly(['status', 'discount', 'tax_percentage'])
            // Tampilkan log jika ada atribut yang "kotor" atau berubah
            ->logOnlyDirty()
            // Pesan log yang akan ditampilkan
            ->setDescriptionForEvent(fn(string $eventName) => "Transaksi Booking #{$this->id} telah di-{$eventName}")
            // Mengelompokkan log berdasarkan nama
            ->useLogName('Transaksi');
    }
}
