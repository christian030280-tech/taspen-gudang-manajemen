<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Peminjaman Aset</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button wire:click="setTab('active')" class="w-1/2 py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'active' ? 'border-[#1557A6] text-[#1557A6]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Sedang Dipinjam
                    </button>
                    <button wire:click="setTab('history')" class="w-1/2 py-4 px-6 text-center border-b-2 font-medium text-sm {{ $tab === 'history' ? 'border-[#1557A6] text-[#1557A6]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Histori Pengembalian
                    </button>
                </nav>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    <input type="text" wire:model.live="search" placeholder="Cari nama peminjam, aset, no aset..." class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                    
                    @if($tab === 'active')
                    <button wire:click="create" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 transition whitespace-nowrap w-full md:w-auto font-medium shadow">
                        <x-lucide-plus class="w-4 h-4 inline-block mr-1" /> Catat Peminjaman
                    </button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Peminjam</th>
                                <th class="px-4 py-3">Aset</th>
                                <th class="px-4 py-3">Waktu Pinjam</th>
                                @if($tab === 'active')
                                    <th class="px-4 py-3">Target Kembali</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                @else
                                    <th class="px-4 py-3">Waktu Kembali</th>
                                    <th class="px-4 py-3">Durasi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($borrowings as $borrow)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3">
                                    <span class="font-bold text-gray-800">{{ $borrow->borrower_name }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">Diproses oleh: {{ $borrow->user->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-[#1557A6]">{{ $borrow->asset->asset_number }}</span>
                                    <br>
                                    {{ $borrow->asset->item->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">{{ $borrow->borrowed_at->format('d M Y, H:i') }}</td>
                                
                                @if($tab === 'active')
                                    <td class="px-4 py-3">
                                        {{ $borrow->expected_return_date ? $borrow->expected_return_date->format('d M Y') : 'Tidak ditentukan' }}
                                        
                                        @if($borrow->expected_return_date && $borrow->expected_return_date->isPast())
                                            <br><span class="text-xs text-red-600 font-bold">Terlambat</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button wire:click="returnAsset({{ $borrow->id }})" class="px-3 py-1 bg-green-100 text-green-700 hover:bg-green-200 hover:text-green-800 font-bold rounded shadow-sm text-sm transition">
                                            Telah Kembali
                                        </button>
                                    </td>
                                @else
                                    <td class="px-4 py-3 text-green-600 font-medium">{{ $borrow->returned_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $borrow->returned_at->diffForHumans($borrow->borrowed_at, true) }}
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                            @if($borrowings->isEmpty())
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    @if($tab === 'active')
                                        Tidak ada aset yang sedang dipinjam.
                                    @else
                                        Belum ada histori pengembalian aset.
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $borrowings->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Peminjaman Baru -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-full md:w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-bold mb-4 border-b pb-2">Catat Peminjaman Aset Baru</h2>
            <form wire:submit.prevent="store">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Peminjam <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="borrower_name" placeholder="Nama Pegawai / Departemen" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                    @error('borrower_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Aset yang Dipinjam <span class="text-red-500">*</span></label>
                    <select wire:model="asset_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                        <option value="">-- Pilih Aset (Hanya status Tersedia) --</option>
                        @foreach($availableAssets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->asset_number }} - {{ $asset->item->name }}</option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Target Tanggal Pengembalian (Opsional)</label>
                    <input type="date" wire:model="expected_return_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                    @error('expected_return_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Keperluan (Opsional)</label>
                    <textarea wire:model="notes" rows="3" placeholder="Contoh: Digunakan untuk dinas luar kota..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]"></textarea>
                    @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100 font-medium">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 font-medium">Proses Pinjam</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
