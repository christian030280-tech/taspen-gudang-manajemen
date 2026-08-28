<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Livewire\Report\ReportGenerator; // Just to reuse the query logic easily
use App\Models\Category;

class ReportController extends Controller
{
    public function print(Request $request)
    {
        // We instantiate the Livewire component just to use its getQueryData method 
        // to avoid duplicating the huge switch-case query logic.
        $generator = new ReportGenerator();
        $generator->report_type = $request->report_type;
        $generator->start_date = $request->start_date;
        $generator->end_date = $request->end_date;
        $generator->category_id = $request->category_id;
        $generator->status = $request->status;

        $data = $generator->getQueryData();
        
        $reportNames = [
            'stock' => 'Laporan Master Persediaan Barang',
            'in' => 'Laporan Mutasi Barang Masuk',
            'out' => 'Laporan Mutasi Barang Keluar',
            'borrowing' => 'Laporan Sirkulasi Peminjaman Aset',
            'asset' => 'Laporan Daftar Inventaris Aset',
            'condition' => 'Laporan Kondisi Aset',
            'auction' => 'Laporan Usulan Lelang (Aset Rusak Berat)',
        ];

        $title = $reportNames[$request->report_type] ?? 'Laporan';
        
        $filters = [];
        if (in_array($request->report_type, ['in', 'out', 'borrowing'])) {
            $filters['Periode'] = \Carbon\Carbon::parse($request->start_date)->format('d M Y') . ' s.d ' . \Carbon\Carbon::parse($request->end_date)->format('d M Y');
        }
        if ($request->category_id) {
            $filters['Kategori'] = Category::find($request->category_id)->name ?? 'Semua';
        }
        if ($request->status) {
            $filters['Status'] = strtoupper(str_replace('_', ' ', $request->status));
        }

        return view('report.print', [
            'data' => $data,
            'report_type' => $request->report_type,
            'title' => $title,
            'filters' => $filters,
        ]);
    }
}
