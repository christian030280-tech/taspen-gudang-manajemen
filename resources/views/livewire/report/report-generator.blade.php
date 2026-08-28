<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pusat Laporan TASPEN</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Filter Panel -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-[#1557A6]">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                    <x-lucide-filter class="w-5 h-5 mr-2 text-[#1557A6]" /> Filter Laporan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Report Type -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Laporan</label>
                        <select wire:model.live="report_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
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
                        <input type="date" wire:model.live="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" wire:model.live="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                    </div>
                    @endif

                    <!-- Category Filter -->
                    @if(in_array($report_type, ['stock', 'in', 'out', 'asset', 'condition', 'auction']))
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kategori Barang</label>
                        <select wire:model.live="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
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
                        <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                            <option value="">-- Semua Status --</option>
                            <option value="borrowed">Sedang Dipinjam</option>
                            <option value="returned">Sudah Dikembalikan</option>
                        </select>
                    </div>
                    @endif

                    @if($report_type === 'asset')
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Aset</label>
                        <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
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
                        <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                            <option value="">-- Semua Kondisi --</option>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>
                    @endif

                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('reports.print', ['report_type' => $report_type, 'start_date' => $start_date, 'end_date' => $end_date, 'category_id' => $category_id, 'status' => $status]) }}" target="_blank" class="px-4 py-2 bg-gray-600 text-white font-bold rounded shadow hover:bg-gray-700 transition flex items-center">
                        <x-lucide-printer class="w-4 h-4 mr-2" /> Print PDF / Kertas
                    </a>
                    <button wire:click="exportExcel" class="px-4 py-2 bg-green-600 text-white font-bold rounded shadow hover:bg-green-700 transition flex items-center">
                        <x-lucide-file-spreadsheet class="w-4 h-4 mr-2" /> Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Panel -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg relative">
            <div wire:loading class="absolute inset-0 bg-white bg-opacity-70 z-10 flex items-center justify-center">
                <x-lucide-loader class="w-8 h-8 animate-spin text-[#1557A6]" />
            </div>

            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Preview Data ({{ count($data) }} Baris)</h3>
                
                <div class="overflow-x-auto max-h-[600px]">
                    @include('livewire.report.partials.table-master', ['data' => $data, 'report_type' => $report_type])
                </div>
            </div>
        </div>

    </div>
</div>
