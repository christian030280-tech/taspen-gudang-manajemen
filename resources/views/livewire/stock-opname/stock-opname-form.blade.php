<x-slot name="header">
    <div class="flex items-center space-x-4">
        <a href="{{ route('stock-opnames.index') }}" class="text-gray-500 hover:text-[#1557A6]">
            <x-lucide-arrow-left class="w-6 h-6" />
        </a>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $stockOpname->status === 'draft' ? 'Input Data Stock Opname' : 'Detail Laporan Stock Opname' }}
        </h2>
    </div>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">No. SO-{{ str_pad($stockOpname->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-sm text-gray-500">Tanggal: {{ $stockOpname->opname_date->format('d M Y') }} | Oleh: {{ $stockOpname->user->name ?? '-' }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        @if($stockOpname->status === 'draft')
                            <span class="px-4 py-2 font-bold text-yellow-700 bg-yellow-100 rounded-full text-sm">STATUS: DRAFT</span>
                        @else
                            <span class="px-4 py-2 font-bold text-green-700 bg-green-100 rounded-full text-sm flex items-center">
                                <x-lucide-check-circle class="w-5 h-5 mr-1" /> FINAL / SELESAI
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($stockOpname->status === 'draft')
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-start">
                <x-lucide-info class="w-6 h-6 text-blue-600 mr-3" />
                <p class="text-sm text-blue-800">
                    <strong>Panduan:</strong> Masukkan jumlah stok fisik yang Anda temukan di gudang ke dalam kolom <strong>Stok Fisik</strong>. Sistem akan otomatis menghitung selisihnya. Kosongkan jika Anda tidak memeriksa barang tersebut. 
                </p>
            </div>
        </div>
        @endif

        <!-- Data Tabel -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 border">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 border-r w-1/4">Barang</th>
                                <th class="px-4 py-3 border-r text-center w-24">Stok Sistem (DB)</th>
                                <th class="px-4 py-3 border-r text-center w-32">Stok Fisik Nyata</th>
                                <th class="px-4 py-3 border-r text-center w-24">Selisih</th>
                                <th class="px-4 py-3">Keterangan / Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockOpname->items as $soItem)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 border-r">
                                    <div class="font-bold text-gray-800">{{ $soItem->item->code }}</div>
                                    <div class="text-gray-600">{{ $soItem->item->name }}</div>
                                </td>
                                <td class="px-4 py-3 border-r text-center font-semibold bg-gray-50">
                                    {{ $soItem->system_stock }}
                                </td>
                                <td class="px-4 py-3 border-r text-center">
                                    @if($stockOpname->status === 'draft')
                                        <input type="number" 
                                               wire:model.lazy="physical_stocks.{{ $soItem->id }}" 
                                               wire:change="calculateDifference({{ $soItem->id }})" 
                                               class="w-full text-center rounded border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]"
                                               placeholder="-">
                                    @else
                                        <span class="font-bold {{ $soItem->physical_stock === null ? 'text-gray-400' : 'text-gray-800' }}">
                                            {{ $soItem->physical_stock ?? 'Tidak dicek' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-r text-center font-bold">
                                    @if($soItem->physical_stock !== null)
                                        @if($soItem->difference > 0)
                                            <span class="text-green-600">+{{ $soItem->difference }}</span>
                                        @elseif($soItem->difference < 0)
                                            <span class="text-red-600">{{ $soItem->difference }}</span>
                                        @else
                                            <span class="text-gray-400">0</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($stockOpname->status === 'draft')
                                        <input type="text" 
                                               wire:model.lazy="item_notes.{{ $soItem->id }}" 
                                               wire:change="updateNote({{ $soItem->id }})" 
                                               class="w-full text-sm rounded border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]"
                                               placeholder="Catatan...">
                                    @else
                                        {{ $soItem->notes ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($stockOpname->status === 'draft')
        <!-- Action Buttons -->
        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border">
            <button wire:click="saveDraft" class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded shadow-sm hover:bg-gray-100 transition">
                Simpan Sebagai Draft
            </button>
            @if(Auth::user()->role === 'admin')
            <button wire:click="completeOpname" onclick="confirm('PENTING: Menyelesaikan Stock Opname akan memperbarui stok database dan membuat log penyesuaian (adjustment). Tindakan ini tidak bisa dibatalkan. Yakin?') || event.stopImmediatePropagation()" class="px-6 py-3 bg-[#1557A6] text-white font-bold rounded shadow hover:bg-blue-800 transition flex items-center">
                Selesaikan & Sesuaikan Stok Sistem <x-lucide-chevron-right class="w-5 h-5 ml-2" />
            </button>
            @endif
        </div>
        @endif

    </div>
</div>
