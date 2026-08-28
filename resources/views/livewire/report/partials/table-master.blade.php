<table class="w-full text-sm text-left text-gray-600 border">
    <thead class="bg-gray-100 border-b">
        <tr>
            <th class="px-4 py-3 border-r">No</th>
            
            @if($report_type === 'stock')
                <th class="px-4 py-3 border-r">Kode Barang</th>
                <th class="px-4 py-3 border-r">Nama Barang</th>
                <th class="px-4 py-3 border-r">Kategori</th>
                <th class="px-4 py-3 border-r text-center">Stok Sistem</th>
                <th class="px-4 py-3 text-center">Satuan</th>
            @endif

            @if(in_array($report_type, ['in', 'out']))
                <th class="px-4 py-3 border-r">Tanggal</th>
                <th class="px-4 py-3 border-r">Barang</th>
                <th class="px-4 py-3 border-r text-center">Jumlah</th>
                <th class="px-4 py-3 border-r">Oleh</th>
                <th class="px-4 py-3">Keterangan</th>
            @endif

            @if($report_type === 'borrowing')
                <th class="px-4 py-3 border-r">Tgl Pinjam</th>
                <th class="px-4 py-3 border-r">Peminjam</th>
                <th class="px-4 py-3 border-r">No Aset</th>
                <th class="px-4 py-3 border-r">Barang</th>
                <th class="px-4 py-3 border-r">Tgl Kembali</th>
                <th class="px-4 py-3">Status</th>
            @endif

            @if(in_array($report_type, ['asset', 'condition', 'auction']))
                <th class="px-4 py-3 border-r">No Aset</th>
                <th class="px-4 py-3 border-r">Master Barang</th>
                <th class="px-4 py-3 border-r">Kategori</th>
                <th class="px-4 py-3 border-r">Kondisi</th>
                <th class="px-4 py-3 border-r">Status</th>
                <th class="px-4 py-3">Lokasi / Penanggung Jawab</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $row)
        <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-3 border-r text-center">{{ $index + 1 }}</td>
            
            @if($report_type === 'stock')
                <td class="px-4 py-3 border-r">{{ $row->code }}</td>
                <td class="px-4 py-3 border-r font-bold">{{ $row->name }}</td>
                <td class="px-4 py-3 border-r">{{ $row->category->name ?? '-' }}</td>
                <td class="px-4 py-3 border-r text-center font-bold {{ $row->current_stock < $row->minimum_stock ? 'text-red-600' : 'text-green-600' }}">{{ $row->current_stock }}</td>
                <td class="px-4 py-3 text-center">{{ $row->unit->name ?? '-' }}</td>
            @endif

            @if(in_array($report_type, ['in', 'out']))
                <td class="px-4 py-3 border-r">{{ $row->transaction_date->format('d M Y') }}</td>
                <td class="px-4 py-3 border-r font-bold">{{ $row->item->name ?? '-' }}</td>
                <td class="px-4 py-3 border-r text-center font-bold">{{ $row->quantity }}</td>
                <td class="px-4 py-3 border-r">{{ $row->user->name ?? '-' }}</td>
                <td class="px-4 py-3">{{ $row->description ?: '-' }}</td>
            @endif

            @if($report_type === 'borrowing')
                <td class="px-4 py-3 border-r">{{ $row->borrowed_at->format('d M Y') }}</td>
                <td class="px-4 py-3 border-r font-bold">{{ $row->borrower_name }}</td>
                <td class="px-4 py-3 border-r">{{ $row->asset->asset_number ?? '-' }}</td>
                <td class="px-4 py-3 border-r">{{ $row->asset->item->name ?? '-' }}</td>
                <td class="px-4 py-3 border-r">{{ $row->returned_at ? $row->returned_at->format('d M Y') : '-' }}</td>
                <td class="px-4 py-3 font-bold uppercase {{ $row->status == 'borrowed' ? 'text-red-600' : 'text-green-600' }}">{{ $row->status == 'borrowed' ? 'Dipinjam' : 'Kembali' }}</td>
            @endif

            @if(in_array($report_type, ['asset', 'condition', 'auction']))
                <td class="px-4 py-3 border-r font-bold text-[#1557A6]">{{ $row->asset_number }}</td>
                <td class="px-4 py-3 border-r">{{ $row->item->name ?? '-' }}</td>
                <td class="px-4 py-3 border-r">{{ $row->item->category->name ?? '-' }}</td>
                <td class="px-4 py-3 border-r uppercase {{ $row->condition == 'baik' ? 'text-green-600' : ($row->condition == 'rusak_ringan' ? 'text-yellow-600' : 'text-red-600') }}">{{ str_replace('_', ' ', $row->condition) }}</td>
                <td class="px-4 py-3 border-r uppercase">{{ $row->status }}</td>
                <td class="px-4 py-3">{{ $row->location->name ?? '-' }} / {{ $row->assigned_to ?? '-' }}</td>
            @endif
        </tr>
        @empty
        <tr>
            <td colspan="10" class="px-4 py-8 text-center text-gray-500 bg-gray-50">
                Tidak ada data yang ditemukan berdasarkan filter tersebut.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
