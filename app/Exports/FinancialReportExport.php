<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Mengumpulkan semua data pembayaran dan pengeluaran.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data PEMASUKAN dari tabel payments
        $incomes = Payment::with('booking.guest')
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->get();

        // Ambil data PENGELUARAN dari tabel expenses
        $expenses = Expense::with('category')
            ->whereBetween('expense_date', [$this->startDate, $this->endDate])
            ->get();

        // Gabungkan kedua koleksi data menjadi satu untuk diproses di 'map'
        return $incomes->concat($expenses);
    }

    /**
     * Mendefinisikan judul kolom di file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe', // Pemasukan atau Pengeluaran
            'Deskripsi',
            'Metode Pembayaran / Kategori',
            'Debit (Pemasukan)',
            'Kredit (Pengeluaran)',
        ];
    }

    /**
     * Memetakan setiap baris data ke format kolom yang diinginkan.
     *
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // Jika baris adalah instance dari Payment (Pemasukan)
        if ($row instanceof Payment) {
            return [
                Carbon::parse($row->payment_date)->format('Y-m-d H:i'),
                'Pemasukan',
                'Pembayaran dari tamu: ' . $row->booking->guest->name . ' (Inv: #' . $row->booking_id . ')',
                Str::title($row->payment_method), // Metode Pembayaran (Tunai, QRIS, dll)
                $row->amount, // Masuk ke kolom Debit
                0,
            ];
        }

        // Jika baris adalah instance dari Expense (Pengeluaran)
        if ($row instanceof Expense) {
            return [
                Carbon::parse($row->expense_date)->format('Y-m-d'),
                'Pengeluaran',
                $row->description,
                $row->category->name, // Kategori Pengeluaran
                0,
                $row->amount, // Masuk ke kolom Kredit
            ];
        }

        return [];
    }
}
