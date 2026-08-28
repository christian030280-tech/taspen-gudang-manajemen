<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Lokasi</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                
                <div class="flex justify-between items-center mb-4">
                    <input type="text" wire:model.live="search" placeholder="Cari lokasi..." class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                    <button wire:click="create" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 transition">
                        Tambah Lokasi
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Nama Lokasi</th>
                                <th class="px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($locations as $location)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3">{{ $location->name }}</td>
                                <td class="px-4 py-3">{{ $location->description }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <button wire:click="edit({{ $location->id }})" class="text-blue-600 hover:text-blue-900">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </button>
                                    @if(Auth::user()->role === 'admin')
                                    <button wire:click="delete({{ $location->id }})" onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700 ml-3">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($locations->isEmpty())
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Tidak ada data ditemukan.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $locations->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-1/3 p-6">
            <h2 class="text-lg font-bold mb-4">{{ $locationId ? 'Edit Lokasi' : 'Tambah Lokasi' }}</h2>
            <form wire:submit.prevent="store">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lokasi</label>
                    <input type="text" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea wire:model="description" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800">Simpan</button>
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
            <p>Apakah Anda yakin ingin menghapus lokasi ini?</p>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" wire:click="$set('isDeleteModalOpen', false)" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="button" wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
