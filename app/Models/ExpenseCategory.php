<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Mendapatkan semua pengeluaran dalam kategori ini.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
