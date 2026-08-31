<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Metric Cards Data
        $totalItems = Item::where('type', 'non_inventory')->count();
        $totalStock = Item::where('type', 'non_inventory')->sum('current_stock');
        
        $totalAset = \App\Models\Asset::count();
        $asetRusak = \App\Models\Asset::whereIn('condition', ['rusak_ringan', 'rusak_berat'])->count();
        $sedangDipinjam = \App\Models\Asset::where('status', 'dipinjam')->count();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $itemsInThisMonth = ItemTransaction::where('type', 'in')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('quantity');

        $itemsOutThisMonth = ItemTransaction::where('type', 'out')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('quantity');

        // Low Stock Items (stok <= minimum_stock dan type == non_inventory)
        $lowStockItems = Item::with(['category', 'unit'])
            ->where('type', 'non_inventory')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->take(5)
            ->get();

        // Recent Activity (Use ItemTransaction instead of ActivityLog)
        $recentActivities = ItemTransaction::with(['user', 'item'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart Data (Last 7 Days)
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $last7Days->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        $transactionsIn = ItemTransaction::select(DB::raw('DATE(transaction_date) as date'), DB::raw('SUM(quantity) as total'))
            ->where('type', 'in')
            ->where('transaction_date', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $transactionsOut = ItemTransaction::select(DB::raw('DATE(transaction_date) as date'), DB::raw('SUM(quantity) as total'))
            ->where('type', 'out')
            ->where('transaction_date', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartDataIn = [];
        $chartDataOut = [];

        foreach ($last7Days as $date) {
            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartDataIn[] = $transactionsIn->get($date, 0);
            $chartDataOut[] = $transactionsOut->get($date, 0);
        }

        return view('livewire.dashboard', [
            'totalItems' => $totalItems,
            'totalStock' => $totalStock,
            'totalAset' => $totalAset,
            'asetRusak' => $asetRusak,
            'sedangDipinjam' => $sedangDipinjam,
            'itemsInThisMonth' => $itemsInThisMonth,
            'itemsOutThisMonth' => $itemsOutThisMonth,
            'lowStockItems' => $lowStockItems,
            'recentActivities' => $recentActivities,
            'chartLabels' => $chartLabels,
            'chartDataIn' => $chartDataIn,
            'chartDataOut' => $chartDataOut,
        ])->layout('layouts.app');
    }
}
