<?php

namespace App\Livewire\Master;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $categoryId, $name, $description;
    
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
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
    }

    public function store()
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->categoryId],
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
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->categoryId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        Category::find($this->categoryId)->delete();
        $this->isDeleteModalOpen = false;
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.master.category-list', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
