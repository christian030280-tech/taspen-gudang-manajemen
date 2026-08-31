<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Histori Transaksi</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Histori Transaksi" description="Lihat seluruh riwayat pergerakan barang masuk dan keluar." />

    <x-taspen-card noPadding="true">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-white gap-4">
            <div class="w-full md:w-1/2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-search class="h-4 w-4 text-gray-400" />
                </div>
                <input type="text" wire:model.live="search" placeholder="Cari barang, no ref, atau sumber/penerima..." class="pl-9 w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
            </div>
            
            <div class="w-full md:w-1/4 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-filter class="h-4 w-4 text-gray-400" />
                </div>
                <select wire:model.live="type" class="pl-9 w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50 text-sm">
                    <option value="">Semua Tipe Transaksi</option>
                    <option value="in">Hanya Barang Masuk</option>
                    <option value="out">Hanya Barang Keluar</option>
                </select>
            </div>
        </div>

        <x-taspen-table :headers="['Tanggal', 'Barang', 'Tipe', 'Jumlah', 'Sumber/Penerima', 'No Ref', 'Operator']">
            @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50 transition-colors text-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex items-center">
                            <x-lucide-calendar class="w-4 h-4 mr-2 text-gray-400" />
                            {{ $trx->transaction_date->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-500 font-mono mb-1">{{ $trx->item->code ?? '-' }}</div>
                        <div class="font-bold text-gray-800">{{ $trx->item->name ?? 'Barang Dihapus' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($trx->type === 'in')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                <x-lucide-arrow-down-to-line class="w-3 h-3 mr-1" /> BARANG MASUK
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                <x-lucide-arrow-up-from-line class="w-3 h-3 mr-1" /> BARANG KELUAR
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-lg {{ $trx->type === 'in' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }} <span class="text-xs font-normal text-gray-500">{{ $trx->item->unit->short_name ?? '' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-800">{{ $trx->source_or_recipient ?? '-' }}</div>
                        @if($trx->department)
                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                <x-lucide-building class="w-3 h-3 mr-1 text-gray-400" />
                                {{ $trx->department }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $trx->reference_number ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold mr-2">
                                {{ substr($trx->user->name ?? 'S', 0, 1) }}
                            </div>
                            {{ $trx->user->name ?? 'Sistem' }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-0">
                        <x-taspen-empty-state icon="clock" title="Pencarian Tidak Ditemukan" description="Riwayat pergerakan barang yang Anda cari tidak ditemukan. Coba ubah kata kunci atau filter tipe transaksi." />
                    </td>
                </tr>
            @endforelse
        </x-taspen-table>
        
        @if($transactions->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $transactions->links() }}
        </div>
        @endif
    </x-taspen-card>
</div>
