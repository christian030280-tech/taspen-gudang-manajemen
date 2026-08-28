<?php

namespace App\Livewire\Asset;

use App\Models\Asset;
use App\Models\Item;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class AssetList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $conditionFilter = '';
    
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $assetId, $item_id, $asset_number, $condition, $status, $location_id, $assigned_to, $notes;

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'asset_number' => 'required|string|max:255',
        'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        'status' => 'required|in:tersedia,dipinjam,perbaikan,dihapus,usul_lelang',
        'location_id' => 'required|exists:locations,id',
        'assigned_to' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    
    public function updatingConditionFilter()
    {
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
        $this->assetId = null;
        $this->item_id = '';
        $this->asset_number = '';
        $this->condition = 'baik';
        $this->status = 'tersedia';
        $this->location_id = '';
        $this->assigned_to = '';
        $this->notes = '';
    }

    public function store()
    {
        $this->rules['asset_number'] = 'required|string|max:255|unique:assets,asset_number,' . $this->assetId;
        $this->validate();

        Asset::updateOrCreate(
            ['id' => $this->assetId],
            [
                'item_id' => $this->item_id,
                'asset_number' => $this->asset_number,
                'condition' => $this->condition,
                'status' => $this->status,
                'location_id' => $this->location_id,
                'assigned_to' => $this->assigned_to,
                'notes' => $this->notes,
            ]
        );

        session()->flash('message', $this->assetId ? 'Aset berhasil diupdate.' : 'Aset berhasil ditambahkan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $this->assetId = $id;
        $this->item_id = $asset->item_id;
        $this->asset_number = $asset->asset_number;
        $this->condition = $asset->condition;
        $this->status = $asset->status;
        $this->location_id = $asset->location_id;
        $this->assigned_to = $asset->assigned_to;
        $this->notes = $asset->notes;

        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->assetId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        Asset::find($this->assetId)->delete();
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Aset berhasil dihapus.');
    }

    public function render()
    {
        $query = Asset::with(['item', 'location'])
            ->orderBy('id', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('asset_number', 'like', '%' . $this->search . '%')
                  ->orWhere('assigned_to', 'like', '%' . $this->search . '%')
                  ->orWhereHas('item', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->conditionFilter) {
            $query->where('condition', $this->conditionFilter);
        }

        return view('livewire.asset.asset-list', [
            'assets' => $query->paginate(15),
            'inventoryItems' => Item::where('type', 'inventory')->get(),
            'locations' => Location::all()
        ])->layout('layouts.app');
    }
}
