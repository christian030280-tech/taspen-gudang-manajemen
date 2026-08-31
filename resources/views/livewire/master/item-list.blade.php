<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Barang</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Data Barang" description="Kelola seluruh data persediaan dan barang inventaris.">
        <x-slot name="actions">
            <x-taspen-button variant="primary" icon="plus" wire:click="create">Tambah Barang</x-taspen-button>
        </x-slot>
    </x-taspen-page-header>

    <x-taspen-card noPadding="true">
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
            <div class="w-full md:w-1/3 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-search class="h-4 w-4 text-gray-400" />
                </div>
                <input type="text" wire:model.live="search" placeholder="Cari kode/nama barang..." class="pl-9 w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
            </div>
            
            <!-- Tambahan filter tipe barang jika diperlukan, untuk sekarang hanya search box sesuai existing -->
        </div>
        
        <x-taspen-table :headers="['Kode', 'Nama & Kategori', 'Tipe', 'Stok & Status', 'Satuan', 'Lokasi', 'Aksi']">
            @forelse($items as $item)
                <tr class="hover:bg-gray-50 transition-colors text-gray-700">
                    <td class="px-6 py-4 font-mono text-sm">{{ $item->code }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800 mb-1">{{ $item->name }}</div>
                        <div class="text-xs text-gray-500">{{ $item->category->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->type === 'inventory')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                <x-lucide-monitor-speaker class="w-3 h-3 mr-1" /> Inventaris/Aset
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <x-lucide-box class="w-3 h-3 mr-1" /> Persediaan
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-lg text-gray-800 mb-1">{{ $item->current_stock }}</div>
                        @if($item->current_stock == 0)
                            <x-taspen-badge variant="danger">HABIS</x-taspen-badge>
                        @elseif($item->current_stock <= $item->minimum_stock)
                            <x-taspen-badge variant="warning">MENIPIS</x-taspen-badge>
                        @else
                            <x-taspen-badge variant="success">AMAN</x-taspen-badge>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $item->unit->short_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->location->name ?? '-' }}</td>
                    <td class="px-6 py-4 flex flex-col space-y-2">
                        <button wire:click="edit({{ $item->id }})" class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded text-xs font-medium transition-colors" title="Edit">
                            <x-lucide-pencil class="w-3 h-3 mr-1" /> Edit
                        </button>
                        @if(Auth::user()->role === 'admin')
                        <button wire:click="confirmDelete({{ $item->id }})" class="inline-flex items-center px-2 py-1 bg-red-50 text-red-700 hover:bg-red-100 rounded text-xs font-medium transition-colors" title="Hapus">
                            <x-lucide-trash-2 class="w-3 h-3 mr-1" /> Hapus
                        </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-0">
                        <x-taspen-empty-state icon="box" title="Belum ada data barang" description="Mulai dengan menambahkan data barang persediaan atau inventaris.">
                            <x-slot name="action">
                                <x-taspen-button variant="ghost" icon="plus" wire:click="create">Tambah Barang</x-taspen-button>
                            </x-slot>
                        </x-taspen-empty-state>
                    </td>
                </tr>
            @endforelse
        </x-taspen-table>
        
        @if($items->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $items->links() }}
        </div>
        @endif
    </x-taspen-card>

    <!-- Form Modal -->
    <x-taspen-modal :isOpen="$isModalOpen" title="{{ $itemId ? 'Edit Barang' : 'Tambah Barang' }}" width="sm:max-w-2xl">
        <x-slot name="closeAction">wire:click="closeModal"</x-slot>
        
        <form wire:submit.prevent="store" id="itemForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="code" placeholder="Contoh: BRG-001" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                    @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="Contoh: Kertas A4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tipe Barang <span class="text-red-500">*</span></label>
                    <select wire:model="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="non_inventory">Persediaan (Non-Inventaris)</option>
                        <option value="inventory">Inventaris (Aset)</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select wire:model="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Satuan <span class="text-red-500">*</span></label>
                    <select wire:model="unit_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->short_name }})</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <select wire:model="location_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Stok Minimum <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="minimum_stock" min="0" placeholder="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                    <span class="text-xs text-gray-500 mt-1 block">Batas stok untuk memicu peringatan 'Stok Menipis'.</span>
                    @error('minimum_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Foto Barang (Opsional)</label>
                    <input type="file" wire:model="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors" accept="image/*">
                    <div wire:loading wire:target="image" class="text-sm text-blue-500 mt-1 flex items-center"><x-lucide-loader class="w-3 h-3 animate-spin mr-1"/> Mengunggah foto...</div>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
                <textarea wire:model="description" rows="3" placeholder="Keterangan tambahan barang..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm"></textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </form>
        
        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="closeModal">Batal</x-taspen-button>
            <x-taspen-button variant="primary" type="submit" form="itemForm" loadingTarget="store">Simpan Data Barang</x-taspen-button>
        </x-slot>
    </x-taspen-modal>

    <!-- Delete Confirmation Modal -->
    <x-taspen-modal :isOpen="$isDeleteModalOpen" title="Hapus Barang?">
        <x-slot name="closeAction">wire:click="$set('isDeleteModalOpen', false)"</x-slot>
        
        <div class="flex items-start">
            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <x-lucide-alert-triangle class="h-6 w-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Penghapusan</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus barang ini? Semua riwayat transaksi barang ini juga akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="$set('isDeleteModalOpen', false)">Batal</x-taspen-button>
            <x-taspen-button variant="danger" wire:click="delete" loadingTarget="delete">Hapus Permanen</x-taspen-button>
        </x-slot>
    </x-taspen-modal>
</div>
