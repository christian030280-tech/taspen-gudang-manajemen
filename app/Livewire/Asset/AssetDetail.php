<?php

namespace App\Livewire\Asset;

use App\Models\Asset;
use App\Models\AssetDocumentation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class AssetDetail extends Component
{
    use WithFileUploads;

    public $asset;
    public $isModalOpen = false;

    public $condition = 'baik';
    public $photo_full;
    public $photo_damage;
    public $damage_description;
    public $action_taken;

    public function mount($id)
    {
        $this->asset = Asset::with(['item.category', 'item.unit', 'location', 'documentations.user' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->condition = $this->asset->condition;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->condition = 'baik';
        $this->photo_full = null;
        $this->photo_damage = null;
        $this->damage_description = '';
        $this->action_taken = '';
    }

    public function store()
    {
        $this->validate([
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'photo_full' => 'required|image|max:2048', // 2MB Max
            'photo_damage' => 'nullable|image|max:2048',
            'damage_description' => 'nullable|string',
            'action_taken' => 'nullable|string',
        ]);

        $photoFullPath = $this->photo_full->store('asset-docs', 'public');
        $photoDamagePath = $this->photo_damage ? $this->photo_damage->store('asset-docs', 'public') : null;

        AssetDocumentation::create([
            'asset_id' => $this->asset->id,
            'user_id' => Auth::id(),
            'condition' => $this->condition,
            'photo_full' => $photoFullPath,
            'photo_damage' => $photoDamagePath,
            'damage_description' => $this->damage_description,
            'action_taken' => $this->action_taken,
        ]);

        // Update the main asset condition
        $this->asset->update([
            'condition' => $this->condition
        ]);

        session()->flash('message', 'Dokumentasi aset berhasil ditambahkan.');
        $this->closeModal();
        
        // Refresh relations
        $this->mount($this->asset->id);
    }

    public function render()
    {
        return view('livewire.asset.asset-detail')
            ->layout('layouts.app');
    }
}
