<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;
    private $totalRows = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $incomes = Payment::with('booking.guest')
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->get();
        $expenses = Expense::with('category')
            ->whereBetween('expense_date', [$this->startDate, $this->endDate])
            ->get();

        $collection = $incomes->concat($expenses);
        $this->totalRows = $collection->count();
        return $collection;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Tipe',
            'Deskripsi',
            'Metode Pembayaran / Kategori',
            'Debit (Pemasukan)',
            'Kredit (Pengeluaran)',
        ];
    }

    public function map($row): array
    {
        if ($row instanceof Payment) {
            return [
                Carbon::parse($row->payment_date)->format('Y-m-d H:i'),
                'Pemasukan',
                'Pembayaran dari tamu: ' . $row->booking->guest->name . ' (Inv: #' . $row->booking_id . ')',
                Str::title($row->payment_method),
                $row->amount,
                0,
            ];
        }
        if ($row instanceof Expense) {
            return [
                Carbon::parse($row->expense_date)->format('Y-m-d'),
                'Pengeluaran',
                $row->description,
                $row->category->name,
                0,
                $row->amount,
            ];
        }
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headerRow = 5;
                $lastDataRow = $headerRow + $this->totalRows;
                $totalRow = $lastDataRow + 1;
                $lastColumn = 'F';

                // 1. Menambahkan Header Laporan di atas tabel
                $sheet->insertNewRowBefore(1, 4);
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue('A1', 'Laporan Keuangan');
                $sheet->setCellValue('A2', 'Hotel Hebat');
                $sheet->setCellValue('A3', 'Periode: ' . Carbon::parse($this->startDate)->format('d M Y') . ' - ' . Carbon::parse($this->endDate)->format('d M Y'));

                // Style untuk header laporan
                $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 2. Style untuk header tabel
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFD3D3D3'); // Warna abu-abu muda

                // 3. Menambahkan baris Total di bawah
                $sheet->setCellValue("D{$totalRow}", 'Total');
                $sheet->setCellValue("E{$totalRow}", "=SUM(E" . ($headerRow + 1) . ":E{$lastDataRow})");
                $sheet->setCellValue("F{$totalRow}", "=SUM(F" . ($headerRow + 1) . ":F{$lastDataRow})");
                $sheet->getStyle("D{$totalRow}:{$lastColumn}{$totalRow}")->getFont()->setBold(true);

                // 4. Memberi border pada seluruh tabel
                $sheet->getStyle("A{$headerRow}:{$lastColumn}{$totalRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // 5. Format Angka
                $sheet->getStyle("E" . ($headerRow + 1) . ":F{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
            },
        ];
    }
}
