<?php

namespace App\Http\Controllers;

use App\Exports\FinancialReportExport;
use App\Models\Booking;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Tentukan rentang tanggal, default bulan ini
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        // Ambil data Pemasukan dari booking yang sudah check-out
        $income = Booking::where('status', 'checked_out')
            ->whereBetween('check_out_date', [$startDate, $endDate])
            ->get()
            ->sum('grand_total');

        // Ambil data Pengeluaran
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');

        // Rincian Pengeluaran per Kategori
        $expenseDetails = Expense::with('category')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->get();

        return view('reports.financial', [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $income - $expenses,
            'expenseDetails' => $expenseDetails,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $fileName = 'laporan-keuangan-'.$startDate->format('d-m-Y').'-'.$endDate->format('d-m-Y').'.xlsx';

        return Excel::download(new FinancialReportExport($startDate, $endDate), $fileName);
    }
}
