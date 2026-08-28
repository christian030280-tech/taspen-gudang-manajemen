<?php

namespace App\Livewire\Transaction;

use App\Models\ItemTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ItemTransaction::with(['item', 'user'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('item', function($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reference_number', 'like', '%' . $this->search . '%')
                ->orWhere('source_or_recipient', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        return view('livewire.transaction.history', [
            'transactions' => $query->paginate(15)
        ])->layout('layouts.app');
    }
}
