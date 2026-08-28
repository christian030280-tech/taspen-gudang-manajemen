<?php

namespace App\Livewire\Borrowing;

use App\Models\Asset;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BorrowingList extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = 'active'; // active, history
    
    public $isModalOpen = false;

    public $asset_id, $borrower_name, $expected_return_date, $notes;

    protected $rules = [
        'asset_id' => 'required|exists:assets,id',
        'borrower_name' => 'required|string|max:255',
        'expected_return_date' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function setTab($tabName)
    {
        $this->tab = $tabName;
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->asset_id = '';
        $this->borrower_name = '';
        $this->expected_return_date = '';
        $this->notes = '';
    }

    public function store()
    {
        $this->validate();

        $asset = Asset::findOrFail($this->asset_id);

        if ($asset->status !== 'tersedia') {
            $this->addError('asset_id', 'Aset ini sedang tidak tersedia.');
            return;
        }

        \DB::transaction(function () use ($asset) {
            Borrowing::create([
                'asset_id' => $this->asset_id,
                'user_id' => Auth::id(),
                'borrower_name' => $this->borrower_name,
                'borrowed_at' => now(),
                'expected_return_date' => $this->expected_return_date ?: null,
                'status' => 'borrowed',
                'notes' => $this->notes,
            ]);

            $asset->update(['status' => 'dipinjam']);
        });

        session()->flash('message', 'Peminjaman aset berhasil dicatat.');
        $this->closeModal();
    }

    public function returnAsset($borrowingId)
    {
        $borrowing = Borrowing::findOrFail($borrowingId);

        if ($borrowing->status === 'returned') {
            return;
        }

        \DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $borrowing->asset->update(['status' => 'tersedia']);
        });

        session()->flash('message', 'Aset berhasil dikembalikan.');
    }

    public function render()
    {
        $query = Borrowing::with(['asset.item', 'user'])
            ->orderBy('created_at', 'desc');

        if ($this->tab === 'active') {
            $query->where('status', 'borrowed');
        } else {
            $query->where('status', 'returned');
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('borrower_name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('asset', function($q2) {
                      $q2->where('asset_number', 'like', '%' . $this->search . '%')
                         ->orWhereHas('item', function($q3) {
                             $q3->where('name', 'like', '%' . $this->search . '%');
                         });
                  });
            });
        }

        return view('livewire.borrowing.borrowing-list', [
            'borrowings' => $query->paginate(15),
            'availableAssets' => Asset::with('item')->where('status', 'tersedia')->get(),
        ])->layout('layouts.app');
    }
}
