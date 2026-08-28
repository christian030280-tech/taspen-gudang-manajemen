<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DynamicReportExport implements FromView, ShouldAutoSize
{
    public $report_type;
    public $data;
    public $start_date;
    public $end_date;

    public function __construct($report_type, $data, $start_date, $end_date)
    {
        $this->report_type = $report_type;
        $this->data = $data;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $reportNames = [
            'stock' => 'Laporan Master Persediaan Barang',
            'in' => 'Laporan Mutasi Barang Masuk',
            'out' => 'Laporan Mutasi Barang Keluar',
            'borrowing' => 'Laporan Sirkulasi Peminjaman Aset',
            'asset' => 'Laporan Daftar Inventaris Aset',
            'condition' => 'Laporan Kondisi Aset',
            'auction' => 'Laporan Usulan Lelang (Aset Rusak Berat)',
        ];

        $title = $reportNames[$this->report_type] ?? 'Laporan';
        
        $filters = [];
        if (in_array($this->report_type, ['in', 'out', 'borrowing'])) {
            $filters['Periode'] = \Carbon\Carbon::parse($this->start_date)->format('d M Y') . ' s.d ' . \Carbon\Carbon::parse($this->end_date)->format('d M Y');
        }

        return view('report.excel', [
            'data' => $this->data,
            'report_type' => $this->report_type,
            'title' => $title,
            'filters' => $filters,
        ]);
    }
}
