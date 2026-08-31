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

<div>
    <x-taspen-page-header 
        title="{{ $stockOpname->status === 'draft' ? 'Input Data Stock Opname' : 'Detail Laporan Stock Opname' }}" 
        description="Periksa dan sesuaikan stok fisik dengan sistem."
    >
        <x-slot name="actions">
            <a href="{{ route('stock-opnames.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-200">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" /> Kembali
            </a>
        </x-slot>
    </x-taspen-page-header>

    <div class="space-y-6">
        
        <!-- Header Info -->
        <x-taspen-card noPadding="true">
            <div class="p-6 bg-white border-b border-gray-100 rounded-xl">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">No. SO-{{ str_pad($stockOpname->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-sm text-gray-500 font-medium">Tanggal: {{ $stockOpname->opname_date->format('d M Y') }} | Oleh: {{ $stockOpname->user->name ?? '-' }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        @if($stockOpname->status === 'draft')
                            <x-taspen-badge variant="warning">STATUS: DRAFT</x-taspen-badge>
                        @else
                            <x-taspen-badge variant="success"><x-lucide-check-circle class="w-4 h-4 mr-1" /> FINAL / SELESAI</x-taspen-badge>
                        @endif
                    </div>
                </div>
            </div>
        </x-taspen-card>

        @if($stockOpname->status === 'draft')
        <div class="bg-blue-50 border border-blue-100 p-5 rounded-xl shadow-sm flex items-start">
            <x-lucide-info class="w-6 h-6 text-[#1557A6] mr-3 shrink-0" />
            <p class="text-sm text-blue-900 leading-relaxed">
                <strong class="font-bold text-[#1557A6]">Panduan:</strong> Masukkan jumlah stok fisik yang Anda temukan di gudang ke dalam kolom <strong class="font-semibold">Stok Fisik Nyata</strong>. Sistem akan otomatis menghitung selisihnya. Kosongkan jika Anda tidak memeriksa barang tersebut. 
            </p>
        </div>
        @endif

        <!-- Data Tabel -->
        <x-taspen-card noPadding="true">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wide text-xs w-1/4">Barang</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wide text-xs text-center w-32">Stok Sistem (DB)</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wide text-xs text-center w-40">Stok Fisik Nyata</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wide text-xs text-center w-24">Selisih</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 uppercase tracking-wide text-xs">Keterangan / Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($stockOpname->items as $soItem)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 mb-1">{{ $soItem->item->code }}</div>
                                <div class="text-xs text-gray-500">{{ $soItem->item->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-600 bg-gray-50/50">
                                {{ $soItem->system_stock }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($stockOpname->status === 'draft')
                                    <input type="number" 
                                           wire:model.lazy="physical_stocks.{{ $soItem->id }}" 
                                           wire:change="calculateDifference({{ $soItem->id }})" 
                                           class="w-full text-center rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm"
                                           placeholder="-">
                                @else
                                    <span class="font-bold {{ $soItem->physical_stock === null ? 'text-gray-400' : 'text-gray-800' }}">
                                        {{ $soItem->physical_stock ?? 'Tidak dicek' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-lg">
                                @if($soItem->physical_stock !== null)
                                    @if($soItem->difference > 0)
                                        <span class="text-green-600">+{{ $soItem->difference }}</span>
                                    @elseif($soItem->difference < 0)
                                        <span class="text-red-600">{{ $soItem->difference }}</span>
                                    @else
                                        <span class="text-gray-300">0</span>
                                    @endif
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($stockOpname->status === 'draft')
                                    <input type="text" 
                                           wire:model.lazy="item_notes.{{ $soItem->id }}" 
                                           wire:change="updateNote({{ $soItem->id }})" 
                                           class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]"
                                           placeholder="Catatan opsional...">
                                @else
                                    {{ $soItem->notes ?? '-' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-taspen-card>

        @if($stockOpname->status === 'draft')
        <!-- Action Buttons -->
        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-50 p-5 rounded-xl border border-gray-100 gap-4 mt-6">
            <x-taspen-button variant="secondary" wire:click="saveDraft" class="w-full md:w-auto text-gray-700 bg-white">
                Simpan Sebagai Draft
            </x-taspen-button>
            
            @if(Auth::user()->role === 'admin')
            <button wire:click="completeOpname" onclick="confirm('PENTING: Menyelesaikan Stock Opname akan memperbarui stok database dan membuat log penyesuaian (adjustment). Tindakan ini tidak bisa dibatalkan. Yakin?') || event.stopImmediatePropagation()" class="w-full md:w-auto px-6 py-2.5 bg-[#1557A6] text-white font-medium rounded-md shadow-sm hover:bg-blue-800 transition-colors flex items-center justify-center">
                Selesaikan & Sesuaikan Stok Sistem <x-lucide-chevron-right class="w-4 h-4 ml-2" />
            </button>
            @endif
        </div>
        @endif

    </div>
</div>
