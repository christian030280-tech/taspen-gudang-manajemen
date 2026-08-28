<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaksi Barang Keluar</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
        
        <!-- Form Section -->
        <div class="md:w-1/2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-orange-500">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-orange-600">Form Permintaan / Barang Keluar</h3>
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="store">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi</label>
                            <input type="date" wire:model="transaction_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                            @error('transaction_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Barang (Yang Memiliki Stok)</label>
                            <select wire:model="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }} (Sisa: {{ $item->current_stock }})</option>
                                @endforeach
                            </select>
                            @error('item_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Diambil</label>
                            <input type="number" wire:model="quantity" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                            @error('quantity') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Penerima (Pegawai)</label>
                                <input type="text" wire:model="source_or_recipient" placeholder="Nama Pegawai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                                @error('source_or_recipient') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Departemen/Unit</label>
                                <input type="text" wire:model="department" placeholder="Cth: SDM/Umum" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                                @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">No. Referensi / Surat</label>
                            <input type="text" wire:model="reference_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                            @error('reference_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tujuan Penggunaan</label>
                            <textarea wire:model="description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="w-full py-3 bg-orange-600 text-white font-bold rounded hover:bg-orange-800 transition">Simpan Barang Keluar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="md:w-1/2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">5 Transaksi Pengeluaran Terakhir</h3>
                    
                    <div class="space-y-4">
                        @forelse($recent_transactions as $trx)
                        <div class="p-4 border border-gray-100 rounded-lg bg-gray-50 flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $trx->item->name ?? 'Barang Dihapus' }}</h4>
                                <p class="text-sm text-gray-500">Diberikan oleh: {{ $trx->user->name ?? 'Sistem' }} pada {{ $trx->transaction_date->format('d M Y') }}</p>
                                @if($trx->source_or_recipient)
                                    <p class="text-sm text-gray-600 mt-1">Kepada: {{ $trx->source_or_recipient }} {{ $trx->department ? '('.$trx->department.')' : '' }}</p>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <span class="px-3 py-1 bg-red-100 text-red-800 font-bold rounded-full text-sm">- {{ $trx->quantity }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            Belum ada riwayat pengeluaran barang.
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
