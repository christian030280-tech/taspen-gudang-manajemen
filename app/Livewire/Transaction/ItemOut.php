<?php

namespace App\Livewire\Transaction;

use App\Models\Item;
use App\Models\ItemTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemOut extends Component
{
    public $item_id, $quantity, $transaction_date, $reference_number, $source_or_recipient, $department, $description;

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
        'transaction_date' => 'required|date',
        'reference_number' => 'nullable|string|max:255',
        'source_or_recipient' => 'required|string|max:255', // Pegawai / Unit yang meminta
        'department' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function store()
    {
        $this->validate();

        $item = Item::findOrFail($this->item_id);

        if ($item->current_stock < $this->quantity) {
            $this->addError('quantity', 'Stok tidak mencukupi! Sisa stok saat ini: ' . $item->current_stock);
            return;
        }

        \DB::transaction(function () use ($item) {
            ItemTransaction::create([
                'item_id' => $this->item_id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'quantity' => $this->quantity,
                'transaction_date' => $this->transaction_date,
                'reference_number' => $this->reference_number,
                'source_or_recipient' => $this->source_or_recipient,
                'department' => $this->department,
                'description' => $this->description,
            ]);

            $item->decrement('current_stock', $this->quantity);
        });

        session()->flash('message', 'Transaksi barang keluar berhasil dicatat.');
        $this->reset(['item_id', 'quantity', 'reference_number', 'source_or_recipient', 'department', 'description']);
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.transaction.item-out', [
            'items' => Item::where('current_stock', '>', 0)->get(),
            'recent_transactions' => ItemTransaction::with(['item', 'user'])
                ->where('type', 'out')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ])->layout('layouts.app');
    }
}
