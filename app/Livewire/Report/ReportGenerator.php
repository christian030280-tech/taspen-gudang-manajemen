<?php

namespace App\Livewire\Report;

use App\Exports\DynamicReportExport;
use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemTransaction;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportGenerator extends Component
{
    public $report_type = 'stock'; // stock, in, out, borrowing, asset, condition, auction
    public $start_date;
    public $end_date;
    public $category_id = '';
    public $status = '';

    public $categories = [];

    protected $queryString = [
        'report_type' => ['except' => 'stock'],
        'start_date' => ['except' => ''],
        'end_date' => ['except' => ''],
        'category_id' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        if (!$this->start_date) $this->start_date = now()->startOfMonth()->toDateString();
        if (!$this->end_date) $this->end_date = now()->endOfMonth()->toDateString();
    }

    public function updatedReportType()
    {
        $this->reset('category_id', 'status');
    }

    public function getQueryData()
    {
        switch ($this->report_type) {
            case 'stock':
                $q = Item::with(['category', 'unit'])->orderBy('name');
                if ($this->category_id) $q->where('category_id', $this->category_id);
                return $q->get();

            case 'in':
                $q = ItemTransaction::with(['item.category', 'user'])
                        ->where('type', 'in')
                        ->whereBetween('transaction_date', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59'])
                        ->orderBy('transaction_date', 'desc');
                if ($this->category_id) {
                    $q->whereHas('item', function($q2) {
                        $q2->where('category_id', $this->category_id);
                    });
                }
                return $q->get();

            case 'out':
                $q = ItemTransaction::with(['item.category', 'user'])
                        ->where('type', 'out')
                        ->whereBetween('transaction_date', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59'])
                        ->orderBy('transaction_date', 'desc');
                if ($this->category_id) {
                    $q->whereHas('item', function($q2) {
                        $q2->where('category_id', $this->category_id);
                    });
                }
                return $q->get();

            case 'borrowing':
                $q = Borrowing::with(['asset.item', 'user'])
                        ->whereBetween('borrowed_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59'])
                        ->orderBy('borrowed_at', 'desc');
                if ($this->status) $q->where('status', $this->status);
                return $q->get();

            case 'asset':
                $q = Asset::with(['item.category', 'location'])->orderBy('asset_number');
                if ($this->category_id) {
                    $q->whereHas('item', function($q2) {
                        $q2->where('category_id', $this->category_id);
                    });
                }
                if ($this->status) $q->where('status', $this->status);
                return $q->get();

            case 'condition':
                $q = Asset::with(['item.category', 'location'])->orderBy('asset_number');
                if ($this->status) $q->where('condition', $this->status); // Using status var for condition filter here
                if ($this->category_id) {
                    $q->whereHas('item', function($q2) {
                        $q2->where('category_id', $this->category_id);
                    });
                }
                return $q->get();

            case 'auction':
                // Aset dengan kondisi rusak berat atau status usul_lelang
                $q = Asset::with(['item.category', 'location'])
                          ->where(function ($query) {
                              $query->where('condition', 'rusak_berat')
                                    ->orWhere('status', 'usul_lelang');
                          })
                          ->orderBy('asset_number');
                if ($this->category_id) {
                    $q->whereHas('item', function($q2) {
                        $q2->where('category_id', $this->category_id);
                    });
                }
                return $q->get();
            
            default:
                return collect([]);
        }
    }

    public function exportExcel()
    {
        $fileName = 'Laporan_TASPEN_' . ucfirst($this->report_type) . '_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new DynamicReportExport($this->report_type, $this->getQueryData(), $this->start_date, $this->end_date), $fileName);
    }

    public function render()
    {
        return view('livewire.report.report-generator', [
            'data' => $this->getQueryData()
        ])->layout('layouts.app');
    }
}
