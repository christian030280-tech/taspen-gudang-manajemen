<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaksi Barang Keluar</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Barang Keluar" description="Catat pengeluaran persediaan atau distribusi inventaris kepada pegawai/unit." />

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Form Section -->
        <div class="lg:w-2/3">
            <x-taspen-card title="Form Pencatatan Barang Keluar" class="border-t-4 border-t-yellow-400">
                <x-slot name="icon"><x-lucide-arrow-up-from-line class="w-5 h-5 text-yellow-500" /></x-slot>

                @if (session()->has('message'))
                    <div class="mb-5 p-4 text-green-800 bg-green-50 border border-green-200 rounded-lg text-sm font-medium flex items-center">
                        <x-lucide-check-circle class="w-5 h-5 mr-2 text-green-600" />
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Alpine component for confirm dialog -->
                <div x-data="{ showConfirm: false }">
                    <form @submit.prevent="showConfirm = true" id="itemOutForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="transaction_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm">
                                @error('transaction_date') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Barang <span class="text-red-500">*</span></label>
                                <select wire:model="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm">
                                    <option value="">-- Cari atau Pilih Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }} (Tersedia: {{ $item->current_stock }} {{ $item->unit->short_name ?? '' }})</option>
                                    @endforeach
                                </select>
                                @error('item_id') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Diambil <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" wire:model="quantity" min="1" placeholder="Misal: 5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm">
                                </div>
                                @error('quantity') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">No. Referensi / Nota <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                                <input type="text" wire:model="reference_number" placeholder="Contoh: NOTA-001" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm">
                                @error('reference_number') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="p-4 bg-yellow-50/50 border border-yellow-100 rounded-lg mb-5">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                                <x-lucide-user-check class="w-4 h-4 mr-2 text-yellow-600" /> Informasi Peminta
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Pegawai Penerima <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="source_or_recipient" placeholder="Nama Pegawai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm">
                                    @error('source_or_recipient') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Departemen/Unit <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                                    <input type="text" wire:model="department" placeholder="Cth: SDM/Umum" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm">
                                    @error('department') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tujuan Penggunaan <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                            <textarea wire:model="description" rows="3" placeholder="Tuliskan keterangan tambahan jika ada..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-400 focus:ring focus:ring-yellow-400 focus:ring-opacity-50 sm:text-sm"></textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <x-taspen-button variant="warning" type="submit" class="px-6 py-2.5 w-full sm:w-auto flex justify-center items-center font-bold">
                                <x-lucide-save class="w-4 h-4 mr-2" /> Simpan Barang Keluar
                            </x-taspen-button>
                        </div>
                    </form>

                    <!-- Confirmation Modal Alpine Component -->
                    <div x-show="showConfirm" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            
                            <div x-show="showConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" @click="showConfirm = false"></div>
                
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                            <div x-show="showConfirm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10 text-yellow-600">
                                            <x-lucide-alert-triangle class="h-6 w-6" />
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Konfirmasi Barang Keluar</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">Apakah Anda yakin data barang keluar sudah benar? Transaksi ini akan <strong>MENGURANGI STOK</strong> barang di gudang. Pastikan pegawai penerima sudah sesuai.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button @click="$wire.store(); showConfirm = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-bold text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Ya, Proses Transaksi
                                    </button>
                                    <button @click="showConfirm = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Periksa Kembali
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-taspen-card>
        </div>

        <!-- Recent Transactions Section -->
        <div class="lg:w-1/3">
            <x-taspen-card title="5 Pengeluaran Terakhir" class="bg-gray-50 border-0 h-full">
                <div class="space-y-4">
                    @forelse($recent_transactions as $trx)
                    <div class="p-4 border border-white bg-white rounded-xl shadow-sm flex flex-col justify-between group hover:shadow-md transition-shadow relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-500"></div>
                        <div class="flex justify-between items-start mb-2 pl-2">
                            <h4 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $trx->item->name ?? 'Barang Dihapus' }}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800 flex-shrink-0 ml-2">
                                -{{ $trx->quantity }} {{ $trx->item->unit->short_name ?? '' }}
                            </span>
                        </div>
                        <div class="pl-2 space-y-1">
                            <div class="flex items-center text-xs text-gray-500">
                                <x-lucide-calendar class="w-3 h-3 mr-1" />
                                {{ $trx->transaction_date->format('d M Y') }}
                            </div>
                            <div class="flex items-center text-xs text-gray-500">
                                <x-lucide-user-check class="w-3 h-3 mr-1" />
                                Oleh: {{ $trx->user->name ?? 'Sistem' }}
                            </div>
                            @if($trx->source_or_recipient)
                            <div class="flex items-center text-xs text-gray-600 mt-2 p-1.5 bg-gray-50 rounded border border-gray-100">
                                <span class="font-semibold text-gray-700 mr-1">Kepada:</span> 
                                <span class="truncate">{{ $trx->source_or_recipient }} {{ $trx->department ? '('.$trx->department.')' : '' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                        <x-taspen-empty-state icon="clock" title="Belum ada transaksi" description="Riwayat barang keluar terbaru akan muncul di sini." />
                    @endforelse
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                    <a href="{{ route('transactions.history') }}" class="text-[#1557A6] hover:text-blue-800 hover:underline text-sm font-bold inline-flex items-center transition-colors">
                        Lihat Semua Histori 
                        <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                    </a>
                </div>
            </x-taspen-card>
        </div>
    </div>
</div>
