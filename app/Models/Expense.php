<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Expense extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    /**
     * Mendapatkan kategori dari pengeluaran ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['description', 'amount', 'expense_date'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Data Pengeluaran '{$this->description}' telah di-{$eventName}")
            ->useLogName('Keuangan');
    }
}
