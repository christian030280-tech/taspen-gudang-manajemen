<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Stock Opname</h2>
</x-slot>

<div>
    <x-taspen-page-header title="Daftar Stock Opname" description="Kelola dan pantau sesi perhitungan fisik (stock opname) di gudang.">
        <x-slot name="actions">
            <x-taspen-button variant="primary" icon="plus" wire:click="create">Mulai Opname Baru</x-taspen-button>
        </x-slot>
    </x-taspen-page-header>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <x-taspen-card noPadding="true">
        <x-taspen-table :headers="['ID SO', 'Tanggal Pelaksanaan', 'Penanggung Jawab', 'Status', 'Aksi']">
            @forelse($stockOpnames as $so)
                <tr class="hover:bg-gray-50 transition-colors text-gray-700">
                    <td class="px-6 py-4 font-bold text-gray-800 whitespace-nowrap">SO-{{ str_pad($so->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $so->opname_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $so->user->name ?? 'Sistem' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($so->status === 'draft')
                            <x-taspen-badge variant="warning">DRAFT</x-taspen-badge>
                        @else
                            <x-taspen-badge variant="success">SELESAI</x-taspen-badge>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex justify-center">
                        @if($so->status === 'draft')
                            <a href="{{ route('stock-opnames.edit', $so->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs uppercase tracking-wider rounded-md shadow-sm transition-colors">
                                <x-lucide-pencil class="w-4 h-4 mr-1" /> Lanjutkan Input
                            </a>
                        @else
                            <a href="{{ route('stock-opnames.edit', $so->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-200 font-bold text-xs uppercase tracking-wider rounded-md shadow-sm transition-colors">
                                <x-lucide-eye class="w-4 h-4 mr-1" /> Lihat Detail
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-0">
                        <x-taspen-empty-state icon="clipboard-check" title="Belum ada sesi Stock Opname" description="Mulai sesi baru untuk melakukan perhitungan fisik barang.">
                            <x-slot name="action">
                                <x-taspen-button variant="ghost" icon="plus" wire:click="create">Mulai Opname Baru</x-taspen-button>
                            </x-slot>
                        </x-taspen-empty-state>
                    </td>
                </tr>
            @endforelse
        </x-taspen-table>
        
        @if($stockOpnames->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $stockOpnames->links() }}
        </div>
        @endif
    </x-taspen-card>
</div>
