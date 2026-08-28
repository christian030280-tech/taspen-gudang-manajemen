<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaksi Barang Masuk</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
        
        <!-- Form Section -->
        <div class="md:w-1/2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-[#1557A6]">Form Barang Masuk</h3>
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="store">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi</label>
                            <input type="date" wire:model="transaction_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            @error('transaction_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Barang</label>
                            <select wire:model="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }} (Stok: {{ $item->current_stock }})</option>
                                @endforeach
                            </select>
                            @error('item_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                            <input type="number" wire:model="quantity" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sumber (Supplier/Vendor)</label>
                            <input type="text" wire:model="source_or_recipient" placeholder="Contoh: PT. ABC" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            @error('source_or_recipient') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">No. Referensi / PO</label>
                            <input type="text" wire:model="reference_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            @error('reference_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan Tambahan</label>
                            <textarea wire:model="description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full py-3 bg-[#1557A6] text-white font-bold rounded hover:bg-blue-800 transition">Simpan Barang Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="md:w-1/2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">5 Transaksi Terakhir</h3>
                    
                    <div class="space-y-4">
                        @forelse($recent_transactions as $trx)
                        <div class="p-4 border border-gray-100 rounded-lg bg-gray-50 flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $trx->item->name ?? 'Barang Dihapus' }}</h4>
                                <p class="text-sm text-gray-500">Oleh: {{ $trx->user->name ?? 'Sistem' }} pada {{ $trx->transaction_date->format('d M Y') }}</p>
                                @if($trx->source_or_recipient)
                                    <p class="text-sm text-gray-600 mt-1">Dari: {{ $trx->source_or_recipient }}</p>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <span class="px-3 py-1 bg-green-100 text-green-800 font-bold rounded-full text-sm">+ {{ $trx->quantity }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            Belum ada riwayat transaksi barang masuk.
                        </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('transactions.history') }}" class="text-[#1557A6] hover:underline text-sm font-medium">Lihat Semua Histori &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
