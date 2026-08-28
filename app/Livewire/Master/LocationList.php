<?php

namespace App\Livewire\Master;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class LocationList extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $locationId, $name, $description;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
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
        $this->locationId = null;
        $this->name = '';
        $this->description = '';
    }

    public function store()
    {
        $this->validate();

        Location::updateOrCreate(
            ['id' => $this->locationId],
            [
                'name' => $this->name,
                'description' => $this->description
            ]
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $this->locationId = $id;
        $this->name = $location->name;
        $this->description = $location->description;

        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->locationId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        Location::find($this->locationId)->delete();
        $this->isDeleteModalOpen = false;
    }

    public function render()
    {
        $locations = Location::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.master.location-list', [
            'locations' => $locations
        ])->layout('layouts.app');
    }
}
