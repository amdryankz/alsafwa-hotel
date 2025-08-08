<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Mendapatkan semua data booking untuk tamu ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
