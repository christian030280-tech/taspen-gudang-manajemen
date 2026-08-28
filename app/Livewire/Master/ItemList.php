<?php

namespace App\Livewire\Master;

use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ItemList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $itemId, $code, $name, $category_id, $unit_id, $location_id, $type, $minimum_stock, $description, $image;

    protected $rules = [
        'code' => 'required|string|max:50',
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'unit_id' => 'required|exists:units,id',
        'location_id' => 'required|exists:locations,id',
        'type' => 'required|in:inventory,non_inventory',
        'minimum_stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ];

    public function updatingSearch()
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
        $this->itemId = null;
        $this->code = '';
        $this->name = '';
        $this->category_id = '';
        $this->unit_id = '';
        $this->location_id = '';
        $this->type = 'non_inventory';
        $this->minimum_stock = 0;
        $this->description = '';
        $this->image = null;
    }

    public function store()
    {
        $this->rules['code'] = 'required|string|max:50|unique:items,code,' . $this->itemId;
        
        $this->validate();

        $itemData = [
            'code' => $this->code,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'location_id' => $this->location_id,
            'type' => $this->type,
            'minimum_stock' => $this->minimum_stock,
            'description' => $this->description,
        ];

        if ($this->image) {
            $itemData['image'] = $this->image->store('items', 'public');
        }

        Item::updateOrCreate(
            ['id' => $this->itemId],
            $itemData
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->itemId = $id;
        $this->code = $item->code;
        $this->name = $item->name;
        $this->category_id = $item->category_id;
        $this->unit_id = $item->unit_id;
        $this->location_id = $item->location_id;
        $this->type = $item->type;
        $this->minimum_stock = $item->minimum_stock;
        $this->description = $item->description;
        $this->image = null; // Don't bind old image instance

        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->itemId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        Item::find($this->itemId)->delete();
        $this->isDeleteModalOpen = false;
    }

    public function render()
    {
        $items = Item::with(['category', 'unit', 'location'])
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.master.item-list', [
            'items' => $items,
            'categories' => Category::all(),
            'units' => Unit::all(),
            'locations' => Location::all()
        ])->layout('layouts.app');
    }
}
