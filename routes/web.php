<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');

    // Master Data
    Route::get('/master/categories', App\Livewire\Master\CategoryList::class)->name('categories.index');
    Route::get('/master/units', App\Livewire\Master\UnitList::class)->name('units.index');
    Route::get('/master/locations', App\Livewire\Master\LocationList::class)->name('locations.index');
    Route::get('/master/items', App\Livewire\Master\ItemList::class)->name('items.index');

    // Assets
    Route::get('/assets', App\Livewire\Asset\AssetList::class)->name('assets.index');
    Route::get('/assets/{id}', App\Livewire\Asset\AssetDetail::class)->name('assets.show');

    // Borrowing
    Route::get('/borrowings', App\Livewire\Borrowing\BorrowingList::class)->name('borrowings.index');

    // Stock Opname
    Route::get('/stock-opnames', App\Livewire\StockOpname\StockOpnameList::class)->name('stock-opnames.index');
    Route::get('/stock-opnames/{id}/edit', App\Livewire\StockOpname\StockOpnameForm::class)->name('stock-opnames.edit');

    // Reports
    Route::get('/reports', App\Livewire\Report\ReportGenerator::class)->name('reports.index');
    Route::get('/reports/print', [App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');

    // Transactions
    Route::get('/transactions/in', App\Livewire\Transaction\ItemIn::class)->name('transactions.in');
    Route::get('/transactions/out', App\Livewire\Transaction\ItemOut::class)->name('transactions.out');
    Route::get('/transactions/history', App\Livewire\Transaction\History::class)->name('transactions.history');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
