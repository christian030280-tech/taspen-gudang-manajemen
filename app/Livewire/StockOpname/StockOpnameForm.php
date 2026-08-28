<?php

namespace App\Livewire\StockOpname;

use App\Models\Item;
use App\Models\ItemTransaction;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockOpnameForm extends Component
{
    public $stockOpname;
    
    // We will bind an array of inputs for physical_stock and notes
    // key: stock_opname_item_id, value: physical_stock
    public $physical_stocks = [];
    public $item_notes = [];

    public function mount($id)
    {
        $this->stockOpname = StockOpname::with('items.item')->findOrFail($id);

        if ($this->stockOpname->status === 'draft' && $this->stockOpname->items->isEmpty()) {
            // Populate all items dynamically when starting
            $items = Item::all();
            foreach ($items as $item) {
                StockOpnameItem::create([
                    'stock_opname_id' => $this->stockOpname->id,
                    'item_id' => $item->id,
                    'system_stock' => $item->current_stock,
                    'physical_stock' => null,
                    'difference' => 0,
                ]);
            }
            // Reload the relation
            $this->stockOpname->load('items.item');
        }

        // Initialize state arrays
        foreach ($this->stockOpname->items as $soItem) {
            $this->physical_stocks[$soItem->id] = $soItem->physical_stock;
            $this->item_notes[$soItem->id] = $soItem->notes;
        }
    }

    public function calculateDifference($soItemId)
    {
        if ($this->stockOpname->status === 'completed') return;

        $soItem = $this->stockOpname->items->firstWhere('id', $soItemId);
        $physicalInput = $this->physical_stocks[$soItemId];
        $physical = ($physicalInput === '' || $physicalInput === null) ? null : (int)$physicalInput;
        
        if ($physical !== null) {
            $difference = $physical - $soItem->system_stock;
        } else {
            $difference = 0;
        }

        $soItem->update([
            'physical_stock' => $physical,
            'difference' => $difference,
            'notes' => $this->item_notes[$soItemId] ?? null,
        ]);

        $this->stockOpname->load('items.item'); // Refresh data safely
    }

    public function updateNote($soItemId)
    {
        if ($this->stockOpname->status === 'completed') return;

        $soItem = $this->stockOpname->items->firstWhere('id', $soItemId);
        $soItem->update([
            'notes' => $this->item_notes[$soItemId] ?? null,
        ]);
    }

    public function saveDraft()
    {
        session()->flash('message', 'Draft Stock Opname berhasil disimpan sementara.');
        return redirect()->route('stock-opnames.index');
    }

    public function completeOpname()
    {
        if ($this->stockOpname->status === 'completed') return;

        DB::transaction(function () {
            foreach ($this->stockOpname->items as $soItem) {
                // If physical stock was inputted and there is a difference
                if ($soItem->physical_stock !== null && $soItem->difference != 0) {
                    // 1. Update Item Stock
                    $item = $soItem->item;
                    $item->update(['current_stock' => $soItem->physical_stock]);

                    // 2. Create transaction log
                    ItemTransaction::create([
                        'item_id' => $item->id,
                        'user_id' => Auth::id(),
                        'type' => 'adjustment',
                        'quantity' => $soItem->difference,
                        'transaction_date' => now(),
                        'reference_number' => 'SO-' . str_pad($this->stockOpname->id, 5, '0', STR_PAD_LEFT),
                        'description' => 'Penyesuaian Stock Opname. ' . ($soItem->notes ? 'Catatan: ' . $soItem->notes : ''),
                    ]);
                }
            }

            $this->stockOpname->update(['status' => 'completed']);
        });

        session()->flash('message', 'Stock Opname berhasil diselesaikan. Stok di database telah disesuaikan dan dicatat di Histori Transaksi.');
        return redirect()->route('stock-opnames.index');
    }

    public function render()
    {
        return view('livewire.stock-opname.stock-opname-form')
            ->layout('layouts.app');
    }
}
