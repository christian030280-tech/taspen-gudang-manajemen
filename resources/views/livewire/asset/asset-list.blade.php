<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Aset (Inventaris)</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    <div class="flex w-full md:w-2/3 space-x-2">
                        <input type="text" wire:model.live="search" placeholder="Cari nama, no aset, penanggung jawab..." class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        
                        <select wire:model.live="statusFilter" class="w-1/4 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="perbaikan">Perbaikan</option>
                            <option value="dihapus">Dihapus</option>
                            <option value="usul_lelang">Usul Lelang</option>
                        </select>
                        
                        <select wire:model.live="conditionFilter" class="w-1/4 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>

                    <button wire:click="create" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 transition whitespace-nowrap w-full md:w-auto">
                        Tambah Aset Baru
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">No. Aset</th>
                                <th class="px-4 py-3">Nama Barang</th>
                                <th class="px-4 py-3">Lokasi</th>
                                <th class="px-4 py-3">Penanggung Jawab</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Kondisi</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($assets as $asset)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $asset->asset_number }}</td>
                                <td class="px-4 py-3">{{ $asset->item->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $asset->location->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $asset->assigned_to ?? 'Tidak Ada' }}</td>
                                <td class="px-4 py-3">
                                    @if($asset->status === 'tersedia')
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">Tersedia</span>
                                    @elseif($asset->status === 'dipinjam')
                                        <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full text-xs">Dipinjam</span>
                                    @elseif($asset->status === 'perbaikan')
                                        <span class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full text-xs">Perbaikan</span>
                                    @elseif($asset->status === 'usul_lelang')
                                        <span class="px-2 py-1 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full text-xs">Usul Lelang</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full text-xs">Dihapus</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($asset->condition === 'baik')
                                        <span class="text-green-600 font-medium">Baik</span>
                                    @elseif($asset->condition === 'rusak_ringan')
                                        <span class="text-yellow-600 font-medium">Rusak Ringan</span>
                                    @else
                                        <span class="text-red-600 font-medium font-bold">Rusak Berat</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    <a href="{{ route('assets.show', $asset->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Detail Aset">
                                        <x-lucide-eye class="w-5 h-5" />
                                    </a>
                                    <button wire:click="edit({{ $asset->id }})" class="text-blue-600 hover:text-blue-900" title="Edit Aset">
                                        <x-lucide-pencil class="w-5 h-5" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $asset->id }})" class="text-red-600 hover:text-red-900" title="Hapus Aset">
                                        <x-lucide-trash-2 class="w-5 h-5" />
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if($assets->isEmpty())
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data aset ditemukan.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $assets->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-bold mb-4">{{ $assetId ? 'Edit Data Aset' : 'Registrasi Aset Baru' }}</h2>
            <form wire:submit.prevent="store">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. Aset (Unik)</label>
                        <input type="text" wire:model="asset_number" placeholder="Cth: TPN-JMB-001" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        @error('asset_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Master Barang (Tipe Inventaris)</label>
                        <select wire:model="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($inventoryItems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                            @endforeach
                        </select>
                        @error('item_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status Ketersediaan</label>
                        <select wire:model="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam / Digunakan</option>
                            <option value="perbaikan">Sedang Perbaikan</option>
                            <option value="dihapus">Dihapus / Afkir</option>
                            <option value="usul_lelang">Usul Lelang</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi Fisik</label>
                        <select wire:model="condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                        @error('condition') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Penempatan</label>
                        <select wire:model="location_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Penanggung Jawab (Opsional)</label>
                        <input type="text" wire:model="assigned_to" placeholder="Nama Pegawai / Divisi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        @error('assigned_to') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Khusus (Spesifikasi, dsb)</label>
                    <textarea wire:model="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50"></textarea>
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800">Simpan Aset</button>
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
            <p>Apakah Anda yakin ingin menghapus data aset ini? Semua riwayat terkait aset ini mungkin akan hilang.</p>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" wire:click="$set('isDeleteModalOpen', false)" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">Batal</button>
                <button type="button" wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
