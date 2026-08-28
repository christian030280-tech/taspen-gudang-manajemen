<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Stock Opname</h2>
        <button wire:click="create" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 transition shadow flex items-center font-bold">
            <x-lucide-plus class="w-5 h-5 mr-2" /> Mulai Opname Baru
        </button>
    </div>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">ID SO</th>
                                <th class="px-4 py-3">Tanggal Pelaksanaan</th>
                                <th class="px-4 py-3">Penanggung Jawab</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach($stockOpnames as $so)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 font-bold text-gray-800">SO-{{ str_pad($so->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3">{{ $so->opname_date->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $so->user->name ?? 'Sistem' }}</td>
                                <td class="px-4 py-3">
                                    @if($so->status === 'draft')
                                        <span class="px-2 py-1 font-semibold text-yellow-700 bg-yellow-100 rounded-full text-xs">DRAFT</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold text-green-700 bg-green-100 rounded-full text-xs">SELESAI</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex space-x-2 justify-center">
                                    @if($so->status === 'draft')
                                        <a href="{{ route('stock-opnames.edit', $so->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm bg-blue-50 px-3 py-1 rounded">Lanjutkan Input</a>
                                    @else
                                        <a href="{{ route('stock-opnames.edit', $so->id) }}" class="text-gray-600 hover:text-gray-900 text-sm bg-gray-50 px-3 py-1 rounded border">Lihat Detail</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($stockOpnames->isEmpty())
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada sesi Stock Opname.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $stockOpnames->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
