<?php

namespace App\Livewire\Transaction;

use App\Models\Item;
use App\Models\ItemTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemIn extends Component
{
    public $item_id, $quantity, $transaction_date, $reference_number, $source_or_recipient, $description;

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
        'transaction_date' => 'required|date',
        'reference_number' => 'nullable|string|max:255',
        'source_or_recipient' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ];

    protected $messages = [
        'item_id.required' => 'Barang wajib dipilih.',
        'quantity.required' => 'Jumlah barang wajib diisi.',
        'quantity.integer' => 'Jumlah barang harus berupa angka.',
        'quantity.min' => 'Jumlah barang minimal 1.',
        'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
    ];

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function store()
    {
        $this->validate();

        $item = Item::findOrFail($this->item_id);

        \DB::transaction(function () use ($item) {
            ItemTransaction::create([
                'item_id' => $this->item_id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $this->quantity,
                'transaction_date' => $this->transaction_date,
                'reference_number' => $this->reference_number,
                'source_or_recipient' => $this->source_or_recipient,
                'description' => $this->description,
            ]);

            $item->increment('current_stock', $this->quantity);
        });

        session()->flash('message', 'Transaksi barang masuk berhasil dicatat.');
        $this->reset(['item_id', 'quantity', 'reference_number', 'source_or_recipient', 'description']);
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.transaction.item-in', [
            'items' => Item::all(),
            'recent_transactions' => ItemTransaction::with(['item', 'user'])
                ->where('type', 'in')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ])->layout('layouts.app');
    }
}
