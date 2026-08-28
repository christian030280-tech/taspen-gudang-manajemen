<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Histori Transaksi</h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                
                <div class="flex justify-between items-center mb-4 space-x-4">
                    <input type="text" wire:model.live="search" placeholder="Cari barang, no ref, atau sumber..." class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                    
                    <select wire:model.live="type" class="w-1/4 rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] focus:ring-opacity-50">
                        <option value="">Semua Tipe Transaksi</option>
                        <option value="in">Barang Masuk</option>
                        <option value="out">Barang Keluar</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Barang</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3">Jumlah</th>
                                <th class="px-4 py-3">Sumber/Penerima</th>
                                <th class="px-4 py-3">No Ref</th>
                                <th class="px-4 py-3">Operator</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($transactions as $trx)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    {{ $trx->item->code ?? '-' }} <br>
                                    <span class="font-bold">{{ $trx->item->name ?? 'Barang Dihapus' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($trx->type === 'in')
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">MASUK</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full text-xs">KELUAR</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-bold text-lg {{ $trx->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $trx->source_or_recipient ?? '-' }}
                                    @if($trx->department)
                                        <br><span class="text-xs text-gray-500">{{ $trx->department }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $trx->reference_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $trx->user->name ?? 'Sistem' }}</td>
                            </tr>
                            @endforeach
                            @if($transactions->isEmpty())
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada riwayat transaksi ditemukan.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
