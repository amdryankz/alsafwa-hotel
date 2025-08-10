<?php

namespace App\Models;

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
        $subtotalAfterDiscount = $this->total_amount - $this->discount;
        $taxAmount = $subtotalAfterDiscount * ($this->tax_percentage / 100);
        return $subtotalAfterDiscount + $taxAmount;
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
