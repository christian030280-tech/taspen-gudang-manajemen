<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Aset (Inventaris)</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Manajemen Aset" description="Kelola daftar aset fisik, status, dan kondisinya.">
        <x-slot name="actions">
            <x-taspen-button variant="primary" icon="plus" wire:click="create">Tambah Aset Baru</x-taspen-button>
        </x-slot>
    </x-taspen-page-header>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <x-taspen-card noPadding="true">
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row gap-4 items-center bg-white">
            <input type="text" wire:model.live="search" placeholder="Cari nama, no aset, penanggung jawab..." class="w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
            
            <select wire:model.live="statusFilter" class="w-full md:w-1/4 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
                <option value="">Semua Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="dipinjam">Dipinjam</option>
                <option value="perbaikan">Perbaikan</option>
                <option value="dihapus">Dihapus</option>
                <option value="usul_lelang">Usul Lelang</option>
            </select>
            
            <select wire:model.live="conditionFilter" class="w-full md:w-1/4 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
                <option value="">Semua Kondisi</option>
                <option value="baik">Baik</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
            </select>
        </div>

        <x-taspen-table :headers="['No. Aset', 'Nama Barang', 'Lokasi', 'Penanggung Jawab', 'Status', 'Kondisi', 'Aksi']">
            @forelse($assets as $asset)
                <tr class="hover:bg-gray-50 transition-colors text-gray-700">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $asset->asset_number }}</td>
                    <td class="px-6 py-4">{{ $asset->item->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $asset->location->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $asset->assigned_to ?? 'Tidak Ada' }}</td>
                    <td class="px-6 py-4">
                        @if($asset->status === 'tersedia')
                            <x-taspen-badge variant="success">Tersedia</x-taspen-badge>
                        @elseif($asset->status === 'dipinjam')
                            <x-taspen-badge variant="warning">Dipinjam</x-taspen-badge>
                        @elseif($asset->status === 'perbaikan')
                            <x-taspen-badge variant="danger">Perbaikan</x-taspen-badge>
                        @elseif($asset->status === 'usul_lelang')
                            <x-taspen-badge variant="info">Usul Lelang</x-taspen-badge>
                        @else
                            <x-taspen-badge variant="secondary">Dihapus</x-taspen-badge>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($asset->condition === 'baik')
                            <span class="text-green-600 font-medium text-sm">Baik</span>
                        @elseif($asset->condition === 'rusak_ringan')
                            <span class="text-yellow-600 font-medium text-sm">Rusak Ringan</span>
                        @else
                            <span class="text-red-600 font-bold text-sm">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex space-x-2 justify-center">
                        <a href="{{ route('assets.show', $asset->id) }}" class="p-1 text-indigo-600 hover:text-indigo-900 rounded hover:bg-indigo-50 transition-colors" title="Detail Aset">
                            <x-lucide-eye class="w-4 h-4" />
                        </a>
                        <button wire:click="edit({{ $asset->id }})" class="p-1 text-blue-600 hover:text-blue-900 rounded hover:bg-blue-50 transition-colors" title="Edit Aset">
                            <x-lucide-pencil class="w-4 h-4" />
                        </button>
                        <button wire:click="confirmDelete({{ $asset->id }})" class="p-1 text-red-600 hover:text-red-900 rounded hover:bg-red-50 transition-colors" title="Hapus Aset">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-0">
                        <x-taspen-empty-state icon="monitor-speaker" title="Belum ada data aset" description="Belum ada aset/inventaris yang tercatat dalam sistem.">
                            <x-slot name="action">
                                <x-taspen-button variant="ghost" icon="plus" wire:click="create">Tambah Aset Baru</x-taspen-button>
                            </x-slot>
                        </x-taspen-empty-state>
                    </td>
                </tr>
            @endforelse
        </x-taspen-table>
        
        @if($assets->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $assets->links() }}
        </div>
        @endif
    </x-taspen-card>

    <!-- Form Modal -->
    <x-taspen-modal :isOpen="$isModalOpen" title="{{ $assetId ? 'Edit Data Aset' : 'Registrasi Aset Baru' }}" width="sm:max-w-2xl">
        <x-slot name="closeAction">wire:click="closeModal"</x-slot>
        
        <form wire:submit.prevent="store" id="assetForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">No. Aset (Unik)</label>
                    <input type="text" wire:model="asset_number" placeholder="Cth: TPN-JMB-001" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                    @error('asset_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Master Barang (Tipe Inventaris)</label>
                    <select wire:model="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($inventoryItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                        @endforeach
                    </select>
                    @error('item_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Status Ketersediaan</label>
                    <select wire:model="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam / Digunakan</option>
                        <option value="perbaikan">Sedang Perbaikan</option>
                        <option value="dihapus">Dihapus / Afkir</option>
                        <option value="usul_lelang">Usul Lelang</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kondisi Fisik</label>
                    <select wire:model="condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                    @error('condition') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Lokasi Penempatan</label>
                    <select wire:model="location_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Penanggung Jawab (Opsional)</label>
                    <input type="text" wire:model="assigned_to" placeholder="Nama Pegawai / Divisi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm">
                    @error('assigned_to') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Catatan Khusus (Spesifikasi, dsb)</label>
                <textarea wire:model="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 sm:text-sm"></textarea>
                @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </form>
        
        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="closeModal">Batal</x-taspen-button>
            <x-taspen-button variant="primary" type="submit" form="assetForm" loadingTarget="store">Simpan Aset</x-taspen-button>
        </x-slot>
    </x-taspen-modal>

    <!-- Delete Confirmation Modal -->
    <x-taspen-modal :isOpen="$isDeleteModalOpen" title="Hapus Aset?">
        <x-slot name="closeAction">wire:click="$set('isDeleteModalOpen', false)"</x-slot>
        
        <p class="text-gray-600 text-sm">Apakah Anda yakin ingin menghapus data aset ini? Semua riwayat terkait aset ini mungkin akan hilang.</p>
        
        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="$set('isDeleteModalOpen', false)">Batal</x-taspen-button>
            <x-taspen-button variant="danger" wire:click="delete" loadingTarget="delete">Hapus</x-taspen-button>
        </x-slot>
    </x-taspen-modal>
</div>
