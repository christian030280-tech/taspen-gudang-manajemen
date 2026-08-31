<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Lokasi</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Lokasi Penyimpanan" description="Kelola letak dan penempatan barang di gudang.">
        <x-slot name="actions">
            <x-taspen-button variant="primary" icon="plus" wire:click="create">Tambah Lokasi</x-taspen-button>
        </x-slot>
    </x-taspen-page-header>

    <x-taspen-card noPadding="true">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <input type="text" wire:model.live="search" placeholder="Cari lokasi..." class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
        </div>
        
        <x-taspen-table :headers="['Nama Lokasi', 'Deskripsi', 'Aksi']">
            @forelse($locations as $location)
                <tr class="hover:bg-gray-50 transition-colors text-gray-700">
                    <td class="px-6 py-4 font-medium">{{ $location->name }}</td>
                    <td class="px-6 py-4">{{ $location->description ?? '-' }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <button wire:click="edit({{ $location->id }})" class="p-1 text-blue-600 hover:text-blue-900 rounded hover:bg-blue-50 transition-colors" title="Edit">
                            <x-lucide-pencil class="w-4 h-4" />
                        </button>
                        @if(Auth::user()->role === 'admin')
                        <button wire:click="delete({{ $location->id }})" onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()" class="p-1 text-red-600 hover:text-red-900 rounded hover:bg-red-50 transition-colors" title="Hapus">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-0">
                        <x-taspen-empty-state icon="map-pin" title="Belum ada data lokasi" description="Belum ada lokasi yang tercatat dalam sistem.">
                            <x-slot name="action">
                                <x-taspen-button variant="ghost" icon="plus" wire:click="create">Tambah Lokasi</x-taspen-button>
                            </x-slot>
                        </x-taspen-empty-state>
                    </td>
                </tr>
            @endforelse
        </x-taspen-table>
        
        @if($locations->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $locations->links() }}
        </div>
        @endif
    </x-taspen-card>

    <!-- Form Modal -->
    <x-taspen-modal :isOpen="$isModalOpen" title="{{ $locationId ? 'Edit Lokasi' : 'Tambah Lokasi' }}">
        <x-slot name="closeAction">wire:click="closeModal"</x-slot>
        
        <form wire:submit.prevent="store" id="locationForm">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Lokasi</label>
                <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                <textarea wire:model="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm"></textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </form>
        
        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="closeModal">Batal</x-taspen-button>
            <x-taspen-button variant="primary" type="submit" form="locationForm" loadingTarget="store">Simpan</x-taspen-button>
        </x-slot>
    </x-taspen-modal>
</div>
