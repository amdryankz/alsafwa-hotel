<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data pemasukan (booking yang sudah check-out)
        $incomes = Booking::with('guest')
            ->where('status', 'checked_out')
            ->whereBetween('check_out_date', [$this->startDate, $this->endDate])
            ->get();

        // Ambil data pengeluaran
        $expenses = Expense::with('category')
            ->whereBetween('expense_date', [$this->startDate, $this->endDate])
            ->get();

        // Gabungkan kedua koleksi data menjadi satu
        return $incomes->concat($expenses)->sortBy('date');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Deskripsi',
            'Kategori',
            'Pemasukan (Rp)',
            'Pengeluaran (Rp)',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Cek apakah baris data adalah instance dari Booking atau Expense
        if ($row instanceof Booking) {
            return [
                $row->check_out_date,
                'Pendapatan dari tamu: ' . $row->guest->name,
                'Akomodasi',
                $row->total_amount,
                0, // Kolom pengeluaran diisi 0
            ];
        }

        if ($row instanceof Expense) {
            return [
                $row->expense_date,
                $row->description,
                $row->category->name,
                0, // Kolom pemasukan diisi 0
                $row->amount,
            ];
        }

        return [];
    }
}
