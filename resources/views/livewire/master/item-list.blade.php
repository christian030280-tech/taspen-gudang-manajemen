<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Barang</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                
                <div class="flex justify-between items-center mb-4">
                    <input type="text" wire:model.live="search" placeholder="Cari kode/nama..." class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                    <button wire:click="create" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 transition">
                        Tambah Barang
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Satuan</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Stok Saat Ini</th>
                                <th class="px-4 py-3">Lokasi</th>
                                <th class="px-4 py-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($items as $item)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3">{{ $item->code }}</td>
                                <td class="px-4 py-3">{{ $item->name }}</td>
                                <td class="px-4 py-3">{{ $item->category->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $item->unit->short_name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($item->type === 'inventory')
                                        <span class="px-2 py-1 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full text-xs">Inventaris/Aset</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">Persediaan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->current_stock }}
                                    @if($item->current_stock <= $item->minimum_stock)
                                        <span class="ml-1 text-red-500 text-xs font-bold">(Menipis)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $item->location->name ?? '-' }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-900">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </button>
                                    @if(Auth::user()->role === 'admin')
                                    <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <x-lucide-trash-2 class="w-5 h-5" />
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($items->isEmpty())
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <x-lucide-box class="w-12 h-12 text-gray-300 mb-2" />
                                        <p class="mb-2">Belum ada data barang.</p>
                                        <button wire:click="create" class="text-sm text-taspen-blue hover:underline">Tambah Barang</button>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $items->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-bold mb-4">{{ $itemId ? 'Edit Barang' : 'Tambah Barang' }}</h2>
            <form wire:submit.prevent="store">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kode Barang</label>
                        <input type="text" wire:model="code" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                        <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Barang</label>
                        <select wire:model="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="non_inventory">Persediaan (Non-Inventaris)</option>
                            <option value="inventory">Inventaris (Aset)</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        <select wire:model="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                        <select wire:model="unit_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                        <select wire:model="location_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok Minimum</label>
                        <input type="number" wire:model="minimum_stock" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        @error('minimum_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea wire:model="description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Foto Barang (Opsional)</label>
                    <input type="file" wire:model="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                    <div wire:loading wire:target="image" class="text-sm text-blue-500 mt-1">Mengunggah foto...</div>
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 disabled:opacity-50 flex items-center" wire:loading.attr="disabled" wire:target="store">
                        <span wire:loading.remove wire:target="store">Simpan</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-1/3 p-6">
            <h2 class="text-lg font-bold mb-4 text-red-600">Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus barang ini? Semua riwayat transaksi barang ini juga akan ikut terhapus atau menyebabkan error.</p>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" wire:click="$set('isDeleteModalOpen', false)" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="button" wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
