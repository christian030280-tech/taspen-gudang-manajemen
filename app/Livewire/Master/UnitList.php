<?php

namespace App\Livewire\Master;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitList extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $unitId, $name, $short_name;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'short_name' => 'required|string|max:50',
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
        $this->unitId = null;
        $this->name = '';
        $this->short_name = '';
    }

    public function store()
    {
        $this->validate();

        Unit::updateOrCreate(
            ['id' => $this->unitId],
            [
                'name' => $this->name,
                'short_name' => $this->short_name
            ]
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unitId = $id;
        $this->name = $unit->name;
        $this->short_name = $unit->short_name;

        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->unitId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        Unit::find($this->unitId)->delete();
        $this->isDeleteModalOpen = false;
    }

    public function render()
    {
        $units = Unit::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.master.unit-list', [
            'units' => $units
        ])->layout('layouts.app');
    }
}
