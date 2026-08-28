<?php

namespace App\Livewire\StockOpname;

use App\Models\StockOpname;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StockOpnameList extends Component
{
    use WithPagination;

    public function create()
    {
        $so = StockOpname::create([
            'opname_date' => now()->toDateString(),
            'status' => 'draft',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('stock-opnames.edit', $so->id);
    }

    public function render()
    {
        return view('livewire.stock-opname.stock-opname-list', [
            'stockOpnames' => StockOpname::with('user')->orderBy('created_at', 'desc')->paginate(15)
        ])->layout('layouts.app');
    }
}
