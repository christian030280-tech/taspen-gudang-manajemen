<x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('assets.index') }}" class="text-gray-500 hover:text-[#1557A6]">
                <x-lucide-arrow-left class="w-6 h-6" />
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Informasi Aset</h2>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 transition font-semibold flex items-center shadow-sm">
            <x-lucide-plus class="w-4 h-4 mr-2" /> Log Dokumentasi
        </button>
    </div>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        @if (session()->has('message'))
            <div class="p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Header Aset -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-2xl font-bold text-gray-800">{{ $asset->item->name ?? 'Aset Tidak Diketahui' }}</h3>
                            
                            @if($asset->status === 'tersedia')
                                <span class="px-3 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">TERSEDIA</span>
                            @elseif($asset->status === 'dipinjam')
                                <span class="px-3 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full text-xs">DIPINJAM</span>
                            @elseif($asset->status === 'perbaikan')
                                <span class="px-3 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full text-xs">PERBAIKAN</span>
                            @elseif($asset->status === 'usul_lelang')
                                <span class="px-3 py-1 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full text-xs">USUL LELANG</span>
                            @else
                                <span class="px-3 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full text-xs">DIHAPUS</span>
                            @endif
                        </div>
                        <p class="text-gray-500 text-sm font-medium tracking-wide">No. Aset: <span class="text-[#1557A6] font-bold">{{ $asset->asset_number }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Informasi Spesifikasi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 bg-white h-full">
                    <h4 class="text-lg font-bold text-gray-700 mb-4 flex items-center border-b pb-2">
                        <x-lucide-file-text class="w-5 h-5 mr-2 text-[#1557A6]" />
                        Spesifikasi & Identitas
                    </h4>
                    
                    <table class="w-full text-sm text-left text-gray-600">
                        <tbody>
                            <tr class="border-b">
                                <th class="py-3 font-semibold text-gray-800 w-1/3">Master Barang</th>
                                <td class="py-3">{{ $asset->item->code }} - {{ $asset->item->name }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-3 font-semibold text-gray-800">Kategori</th>
                                <td class="py-3">{{ $asset->item->category->name ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-3 font-semibold text-gray-800">Kondisi Fisik</th>
                                <td class="py-3">
                                    @if($asset->condition === 'baik')
                                        <span class="px-2 py-1 bg-green-50 text-green-700 font-bold rounded">Baik</span>
                                    @elseif($asset->condition === 'rusak_ringan')
                                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 font-bold rounded">Rusak Ringan</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-50 text-red-700 font-bold rounded">Rusak Berat</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="py-3 font-semibold text-gray-800 align-top">Catatan Khusus</th>
                                <td class="py-3">{{ $asset->notes ?: 'Tidak ada catatan.' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Penempatan & Penanggung Jawab -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 bg-white h-full">
                    <h4 class="text-lg font-bold text-gray-700 mb-4 flex items-center border-b pb-2">
                        <x-lucide-map-pin class="w-5 h-5 mr-2 text-[#1557A6]" />
                        Lokasi & Kepemilikan
                    </h4>
                    
                    <table class="w-full text-sm text-left text-gray-600">
                        <tbody>
                            <tr class="border-b">
                                <th class="py-3 font-semibold text-gray-800 w-1/3">Lokasi Saat Ini</th>
                                <td class="py-3 font-medium text-[#1557A6]">{{ $asset->location->name ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-3 font-semibold text-gray-800">Deskripsi Lokasi</th>
                                <td class="py-3">{{ $asset->location->description ?? '-' }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-3 font-semibold text-gray-800">Penanggung Jawab</th>
                                <td class="py-3 font-bold">{{ $asset->assigned_to ?: 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 font-semibold text-gray-800">Terakhir Diupdate</th>
                                <td class="py-3">{{ $asset->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>

        <!-- Histori Dokumentasi -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8 border-t-4 border-[#1557A6]">
            <div class="p-6 bg-white">
                <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <x-lucide-camera class="w-6 h-6 mr-3 text-[#1557A6]" />
                    Histori Dokumentasi Kondisi
                </h4>

                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                    
                    @forelse($asset->documentations->sortByDesc('created_at') as $doc)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                            @if($doc->condition === 'baik')
                                <x-lucide-check-circle class="w-5 h-5 text-green-500" />
                            @else
                                <x-lucide-alert-circle class="w-5 h-5 text-red-500" />
                            @endif
                        </div>
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-lg border border-gray-100 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-gray-800">{{ $doc->user->name ?? 'Sistem' }}</div>
                                <div class="text-xs text-gray-500">{{ $doc->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                @if($doc->condition === 'baik')
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 font-bold rounded text-xs uppercase">Kondisi Baik</span>
                                @elseif($doc->condition === 'rusak_ringan')
                                    <span class="px-2 py-0.5 bg-yellow-50 text-yellow-700 font-bold rounded text-xs uppercase">Rusak Ringan</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-50 text-red-700 font-bold rounded text-xs uppercase">Rusak Berat</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Foto Utuh:</p>
                                    <a href="{{ Storage::url($doc->photo_full) }}" target="_blank">
                                        <img src="{{ Storage::url($doc->photo_full) }}" alt="Foto Utuh" class="w-full h-24 object-cover rounded border hover:opacity-75 transition">
                                    </a>
                                </div>
                                @if($doc->photo_damage)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Foto Kerusakan:</p>
                                    <a href="{{ Storage::url($doc->photo_damage) }}" target="_blank">
                                        <img src="{{ Storage::url($doc->photo_damage) }}" alt="Foto Kerusakan" class="w-full h-24 object-cover rounded border border-red-200 hover:opacity-75 transition">
                                    </a>
                                </div>
                                @endif
                            </div>

                            @if($doc->damage_description)
                            <div class="text-sm text-gray-600 mb-2 p-2 bg-gray-50 rounded">
                                <span class="font-bold text-xs text-gray-400 block uppercase">Detail Kerusakan</span>
                                {{ $doc->damage_description }}
                            </div>
                            @endif

                            @if($doc->action_taken)
                            <div class="text-sm text-blue-700 p-2 bg-blue-50 rounded border border-blue-100">
                                <span class="font-bold text-xs text-blue-400 block uppercase">Tindak Lanjut</span>
                                {{ $doc->action_taken }}
                            </div>
                            @endif

                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-500 relative z-10 bg-white">
                        <x-lucide-image-off class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                        <p>Belum ada dokumentasi kondisi untuk aset ini.</p>
                    </div>
                    @endforelse

                </div>

            </div>
        </div>
        
    </div>

    <!-- Modal Upload Dokumentasi -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
        <div class="bg-white rounded-lg w-full md:w-1/2 lg:w-1/3 p-6 max-h-[90vh] overflow-y-auto relative">
            
            <div wire:loading wire:target="store" class="absolute inset-0 z-10 bg-white bg-opacity-80 flex flex-col items-center justify-center rounded-lg">
                <x-lucide-loader class="w-10 h-10 text-[#1557A6] animate-spin mb-2" />
                <p class="font-bold text-[#1557A6]">Mengupload Foto...</p>
            </div>

            <h2 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Tambah Log Dokumentasi</h2>
            <form wire:submit.prevent="store">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi Aset Saat Ini <span class="text-red-500">*</span></label>
                    <select wire:model.live="condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6]">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                    @error('condition') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Mengubah ini akan mengupdate kondisi utama aset secara otomatis.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Foto Aset Utuh <span class="text-red-500">*</span></label>
                    <input type="file" wire:model="photo_full" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#1557A6] hover:file:bg-blue-100">
                    @error('photo_full') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @if ($photo_full)
                        <img src="{{ $photo_full->temporaryUrl() }}" class="mt-2 h-20 object-cover rounded border">
                    @endif
                </div>

                @if($condition !== 'baik')
                <div class="mb-4 p-4 border border-orange-200 bg-orange-50 rounded-lg">
                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Foto Bagian Rusak</label>
                        <input type="file" wire:model="photo_damage" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200">
                        @error('photo_damage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if ($photo_damage)
                            <img src="{{ $photo_damage->temporaryUrl() }}" class="mt-2 h-20 object-cover rounded border border-orange-300">
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Kerusakan</label>
                        <textarea wire:model="damage_description" rows="2" placeholder="Jelaskan kerusakan yang terjadi..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500"></textarea>
                        @error('damage_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-1">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tindak Lanjut (Action Plan)</label>
                        <textarea wire:model="action_taken" rows="2" placeholder="Contoh: Diajukan perbaikan ke vendor..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500"></textarea>
                        @error('action_taken') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100 font-medium">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1557A6] text-white rounded hover:bg-blue-800 font-medium flex items-center">
                        Simpan Log
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
