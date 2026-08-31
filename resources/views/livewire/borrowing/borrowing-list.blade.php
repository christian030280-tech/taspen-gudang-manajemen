<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Peminjaman Aset</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Peminjaman Aset" description="Kelola dan pantau peminjaman aset inventaris oleh pegawai atau departemen." />

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <x-taspen-card noPadding="true" class="mb-6">
        <div class="border-b border-gray-100 flex overflow-x-auto">
            <button wire:click="setTab('active')" class="min-w-[150px] flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors {{ $tab === 'active' ? 'border-[#1557A6] text-[#1557A6]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Sedang Dipinjam
            </button>
            <button wire:click="setTab('history')" class="min-w-[150px] flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors {{ $tab === 'history' ? 'border-[#1557A6] text-[#1557A6]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Histori Pengembalian
            </button>
        </div>
        
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
            <input type="text" wire:model.live="search" placeholder="Cari nama peminjam, aset, no aset..." class="w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
            
            @if($tab === 'active')
            <x-taspen-button variant="primary" icon="plus" wire:click="create" class="w-full md:w-auto">Catat Peminjaman</x-taspen-button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap text-sm text-left text-gray-600">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-6 py-3">Peminjam</th>
                        <th class="px-6 py-3">Aset</th>
                        <th class="px-6 py-3">Waktu Pinjam</th>
                        @if($tab === 'active')
                            <th class="px-6 py-3">Target Kembali</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        @else
                            <th class="px-6 py-3">Waktu Kembali</th>
                            <th class="px-6 py-3">Durasi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($borrowings as $borrow)
                        <tr class="hover:bg-gray-50 transition-colors text-gray-700">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800 block mb-1">{{ $borrow->borrower_name }}</span>
                                <span class="text-xs text-gray-500">Diproses oleh: {{ $borrow->user->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#1557A6] block mb-1">{{ $borrow->asset->asset_number }}</span>
                                <span class="text-xs text-gray-600">{{ $borrow->asset->item->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $borrow->borrowed_at->format('d M Y, H:i') }}</td>
                            
                            @if($tab === 'active')
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $borrow->expected_return_date ? $borrow->expected_return_date->format('d M Y') : 'Tidak ditentukan' }}
                                    
                                    @if($borrow->expected_return_date && $borrow->expected_return_date->isPast())
                                        <br><span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded-full uppercase">Terlambat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="returnAsset({{ $borrow->id }})" class="px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 hover:text-green-800 font-bold rounded-md shadow-sm text-xs uppercase tracking-wider transition-colors inline-flex items-center" title="Tandai Telah Kembali">
                                        <x-lucide-check-circle class="w-4 h-4 mr-1" /> Kembali
                                    </button>
                                </td>
                            @else
                                <td class="px-6 py-4 text-green-600 font-medium whitespace-nowrap">{{ $borrow->returned_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                    {{ $borrow->returned_at->diffForHumans($borrow->borrowed_at, true) }}
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tab === 'active' ? 5 : 4 }}" class="p-0">
                                @if($tab === 'active')
                                    <x-taspen-empty-state icon="arrow-right-left" title="Tidak ada peminjaman" description="Saat ini tidak ada aset yang sedang dipinjam.">
                                        <x-slot name="action">
                                            <x-taspen-button variant="ghost" icon="plus" wire:click="create">Catat Peminjaman</x-taspen-button>
                                        </x-slot>
                                    </x-taspen-empty-state>
                                @else
                                    <x-taspen-empty-state icon="history" title="Belum ada histori" description="Belum ada catatan pengembalian aset." />
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($borrowings->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $borrowings->links() }}
        </div>
        @endif
    </x-taspen-card>

    <!-- Modal Peminjaman Baru -->
    <x-taspen-modal :isOpen="$isModalOpen" title="Catat Peminjaman Aset Baru" width="sm:max-w-2xl">
        <x-slot name="closeAction">wire:click="closeModal"</x-slot>
        
        <form wire:submit.prevent="store" id="borrowForm">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Peminjam <span class="text-red-500">*</span></label>
                <input type="text" wire:model="borrower_name" placeholder="Nama Pegawai / Departemen" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                @error('borrower_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Aset yang Dipinjam <span class="text-red-500">*</span></label>
                <select wire:model="asset_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                    <option value="">-- Pilih Aset (Hanya status Tersedia) --</option>
                    @foreach($availableAssets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->asset_number }} - {{ $asset->item->name }}</option>
                    @endforeach
                </select>
                @error('asset_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Target Tanggal Pengembalian (Opsional)</label>
                <input type="date" wire:model="expected_return_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                @error('expected_return_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Catatan Keperluan (Opsional)</label>
                <textarea wire:model="notes" rows="3" placeholder="Contoh: Digunakan untuk dinas luar kota..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm"></textarea>
                @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </form>
        
        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="closeModal">Batal</x-taspen-button>
            <x-taspen-button variant="primary" type="submit" form="borrowForm" loadingTarget="store">Proses Pinjam</x-taspen-button>
        </x-slot>
    </x-taspen-modal>
</div>
