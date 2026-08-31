<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pusat Laporan TASPEN</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Pusat Laporan" description="Hasilkan, cetak, dan ekspor berbagai laporan gudang dan inventaris." />

    <div class="space-y-6 relative">
        
        <!-- Filter Panel -->
        <x-taspen-card title="Filter Laporan" class="border-t-4 border-t-[#1557A6]">
            <x-slot name="icon"><x-lucide-filter class="w-5 h-5 text-[#1557A6]" /></x-slot>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Laporan</label>
                    <select wire:model.live="report_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                        <option value="stock">Stok Persediaan Master</option>
                        <option value="in">Histori Barang Masuk</option>
                        <option value="out">Histori Barang Keluar</option>
                        <option value="borrowing">Sirkulasi Peminjaman Aset</option>
                        <option value="asset">Daftar Inventaris/Aset</option>
                        <option value="condition">Kondisi Aset</option>
                        <option value="auction">Usul Lelang (Rusak Berat)</option>
                    </select>
                </div>

                <!-- Date Range (only for transaction/borrowing) -->
                @if(in_array($report_type, ['in', 'out', 'borrowing']))
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" wire:model.live="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" wire:model.live="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                </div>
                @endif

                <!-- Category Filter -->
                @if(in_array($report_type, ['stock', 'in', 'out', 'asset', 'condition', 'auction']))
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Barang</label>
                    <select wire:model.live="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Status Filter -->
                @if($report_type === 'borrowing')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Peminjaman</label>
                    <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="borrowed">Sedang Dipinjam</option>
                        <option value="returned">Sudah Dikembalikan</option>
                    </select>
                </div>
                @endif

                @if($report_type === 'asset')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status Aset</label>
                    <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="perbaikan">Perbaikan</option>
                        <option value="dihapus">Dihapus / Dilelang</option>
                    </select>
                </div>
                @endif

                @if($report_type === 'condition')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kondisi</label>
                    <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                        <option value="">-- Semua Kondisi --</option>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>
                @endif

            </div>

            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('reports.print', ['report_type' => $report_type, 'start_date' => $start_date, 'end_date' => $end_date, 'category_id' => $category_id, 'status' => $status]) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white font-bold rounded-md shadow-sm hover:bg-gray-700 transition-colors w-full sm:w-auto">
                    <x-lucide-printer class="w-4 h-4 mr-2" /> Print PDF / Kertas
                </a>
                <button wire:click="exportExcel" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-bold rounded-md shadow-sm hover:bg-green-700 transition-colors w-full sm:w-auto">
                    <x-lucide-file-spreadsheet class="w-4 h-4 mr-2" /> Export Excel
                </button>
            </div>
        </x-taspen-card>

        <!-- Preview Panel -->
        <x-taspen-card title="Preview Data ({{ count($data) }} Baris)" noPadding="true" class="relative">
            <div wire:loading class="absolute inset-0 bg-white/70 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl">
                <div class="flex flex-col items-center bg-white p-4 rounded-lg shadow-lg">
                    <x-lucide-loader class="w-8 h-8 animate-spin text-[#1557A6] mb-2" />
                    <span class="font-bold text-[#1557A6]">Memuat Data...</span>
                </div>
            </div>

            <div class="overflow-x-auto max-h-[600px]">
                @include('livewire.report.partials.table-master', ['data' => $data, 'report_type' => $report_type])
            </div>
        </x-taspen-card>

    </div>
</div>
