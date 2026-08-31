<div>
    <x-taspen-page-header title="Detail Informasi Aset" description="Spesifikasi, lokasi, dan histori kondisi aset fisik.">
        <x-slot name="actions">
            <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-200">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" /> Kembali
            </a>
            <x-taspen-button variant="primary" icon="plus" wire:click="openModal">Log Dokumentasi</x-taspen-button>
        </x-slot>
    </x-taspen-page-header>

    <div class="space-y-6">
        @if (session()->has('message'))
            <div class="p-4 text-green-700 bg-green-100 rounded-lg text-sm font-medium">
                {{ session('message') }}
            </div>
        @endif

        <!-- Header Aset -->
        <x-taspen-card noPadding="true">
            <div class="p-6 bg-white border-b border-gray-100 rounded-t-xl">
                <div class="flex items-center space-x-3 mb-2">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $asset->item->name ?? 'Aset Tidak Diketahui' }}</h3>
                    
                    @if($asset->status === 'tersedia')
                        <x-taspen-badge variant="success">TERSEDIA</x-taspen-badge>
                    @elseif($asset->status === 'dipinjam')
                        <x-taspen-badge variant="warning">DIPINJAM</x-taspen-badge>
                    @elseif($asset->status === 'perbaikan')
                        <x-taspen-badge variant="danger">PERBAIKAN</x-taspen-badge>
                    @elseif($asset->status === 'usul_lelang')
                        <x-taspen-badge variant="info">USUL LELANG</x-taspen-badge>
                    @else
                        <x-taspen-badge variant="secondary">DIHAPUS</x-taspen-badge>
                    @endif
                </div>
                <p class="text-gray-500 text-sm font-medium tracking-wide">No. Aset: <span class="text-[#1557A6] font-bold">{{ $asset->asset_number }}</span></p>
            </div>
        </x-taspen-card>

        <!-- Detail Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Spesifikasi -->
            <x-taspen-card title="Spesifikasi & Identitas">
                <x-slot name="icon"><x-lucide-file-text class="w-5 h-5 text-[#1557A6]" /></x-slot>
                
                <table class="w-full text-sm text-left text-gray-600">
                    <tbody>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 font-semibold text-gray-800 w-1/3">Master Barang</th>
                            <td class="py-3">{{ $asset->item->code }} - {{ $asset->item->name }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 font-semibold text-gray-800">Kategori</th>
                            <td class="py-3">{{ $asset->item->category->name ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 font-semibold text-gray-800">Kondisi Fisik</th>
                            <td class="py-3">
                                @if($asset->condition === 'baik')
                                    <span class="text-green-600 font-bold">Baik</span>
                                @elseif($asset->condition === 'rusak_ringan')
                                    <span class="text-yellow-600 font-bold">Rusak Ringan</span>
                                @else
                                    <span class="text-red-600 font-bold">Rusak Berat</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="py-3 font-semibold text-gray-800 align-top">Catatan Khusus</th>
                            <td class="py-3">{{ $asset->notes ?: 'Tidak ada catatan.' }}</td>
                        </tr>
                    </tbody>
                </table>
            </x-taspen-card>

            <!-- Penempatan & Penanggung Jawab -->
            <x-taspen-card title="Lokasi & Kepemilikan">
                <x-slot name="icon"><x-lucide-map-pin class="w-5 h-5 text-[#1557A6]" /></x-slot>
                
                <table class="w-full text-sm text-left text-gray-600">
                    <tbody>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 font-semibold text-gray-800 w-1/3">Lokasi Saat Ini</th>
                            <td class="py-3 font-medium text-[#1557A6]">{{ $asset->location->name ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 font-semibold text-gray-800">Deskripsi Lokasi</th>
                            <td class="py-3">{{ $asset->location->description ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <th class="py-3 font-semibold text-gray-800">Penanggung Jawab</th>
                            <td class="py-3 font-bold">{{ $asset->assigned_to ?: 'Belum ditentukan' }}</td>
                        </tr>
                        <tr>
                            <th class="py-3 font-semibold text-gray-800">Terakhir Diupdate</th>
                            <td class="py-3">{{ $asset->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </x-taspen-card>
        </div>

        <!-- Histori Dokumentasi -->
        <x-taspen-card title="Histori Dokumentasi Kondisi" class="border-t-4 border-[#1557A6]">
            <x-slot name="icon"><x-lucide-camera class="w-5 h-5 text-[#1557A6]" /></x-slot>

            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent mt-4">
                
                @forelse($asset->documentations->sortByDesc('created_at') as $doc)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                        @if($doc->condition === 'baik')
                            <x-lucide-check-circle class="w-5 h-5 text-green-500" />
                        @else
                            <x-lucide-alert-circle class="w-5 h-5 text-red-500" />
                        @endif
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-bold text-gray-800">{{ $doc->user->name ?? 'Sistem' }}</div>
                            <div class="text-xs text-gray-500 font-medium">{{ $doc->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        
                        <div class="mb-4">
                            @if($doc->condition === 'baik')
                                <span class="px-2 py-1 bg-green-50 text-green-700 font-bold rounded text-xs uppercase">Kondisi Baik</span>
                            @elseif($doc->condition === 'rusak_ringan')
                                <span class="px-2 py-1 bg-yellow-50 text-yellow-700 font-bold rounded text-xs uppercase">Rusak Ringan</span>
                            @else
                                <span class="px-2 py-1 bg-red-50 text-red-700 font-bold rounded text-xs uppercase">Rusak Berat</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1 font-semibold">Foto Utuh:</p>
                                <a href="{{ Storage::url($doc->photo_full) }}" target="_blank" class="block">
                                    <img src="{{ Storage::url($doc->photo_full) }}" alt="Foto Utuh" class="w-full h-28 object-cover rounded-lg border border-gray-200 hover:opacity-75 transition-opacity">
                                </a>
                            </div>
                            @if($doc->photo_damage)
                            <div>
                                <p class="text-xs text-gray-500 mb-1 font-semibold">Foto Kerusakan:</p>
                                <a href="{{ Storage::url($doc->photo_damage) }}" target="_blank" class="block">
                                    <img src="{{ Storage::url($doc->photo_damage) }}" alt="Foto Kerusakan" class="w-full h-28 object-cover rounded-lg border border-red-200 hover:opacity-75 transition-opacity">
                                </a>
                            </div>
                            @endif
                        </div>

                        @if($doc->damage_description)
                        <div class="text-sm text-gray-600 mb-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="font-bold text-xs text-gray-500 block uppercase mb-1">Detail Kerusakan</span>
                            {{ $doc->damage_description }}
                        </div>
                        @endif

                        @if($doc->action_taken)
                        <div class="text-sm text-blue-800 p-3 bg-[#EAF3FF] rounded-lg border border-[#1557A6]/20">
                            <span class="font-bold text-xs text-[#1557A6] block uppercase mb-1">Tindak Lanjut</span>
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
        </x-taspen-card>
    </div>

    <!-- Modal Upload Dokumentasi -->
    <x-taspen-modal :isOpen="$isModalOpen" title="Tambah Log Dokumentasi" width="sm:max-w-xl">
        <x-slot name="closeAction">wire:click="closeModal"</x-slot>
        
        <div wire:loading wire:target="store" class="absolute inset-0 z-10 bg-white bg-opacity-80 flex flex-col items-center justify-center rounded-lg">
            <x-lucide-loader class="w-10 h-10 text-[#1557A6] animate-spin mb-2" />
            <p class="font-bold text-[#1557A6]">Mengupload Foto...</p>
        </div>

        <form wire:submit.prevent="store" id="docForm">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Kondisi Aset Saat Ini <span class="text-red-500">*</span></label>
                <select wire:model.live="condition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1557A6] focus:ring focus:ring-[#1557A6] sm:text-sm">
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
                @error('condition') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Mengubah ini akan mengupdate kondisi utama aset secara otomatis.</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Foto Aset Utuh <span class="text-red-500">*</span></label>
                <input type="file" wire:model="photo_full" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#1557A6] hover:file:bg-blue-100 transition-colors">
                @error('photo_full') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                @if ($photo_full)
                    <img src="{{ $photo_full->temporaryUrl() }}" class="mt-2 h-24 object-cover rounded-lg border border-gray-200">
                @endif
            </div>

            @if($condition !== 'baik')
            <div class="mb-4 p-4 border border-orange-200 bg-orange-50 rounded-lg">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Foto Bagian Rusak</label>
                    <input type="file" wire:model="photo_damage" accept="image/png, image/jpeg, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200 transition-colors">
                    @error('photo_damage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    @if ($photo_damage)
                        <img src="{{ $photo_damage->temporaryUrl() }}" class="mt-2 h-24 object-cover rounded-lg border border-orange-300">
                    @endif
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi Kerusakan</label>
                    <textarea wire:model="damage_description" rows="2" placeholder="Jelaskan kerusakan yang terjadi..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 sm:text-sm"></textarea>
                    @error('damage_description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-1">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Tindak Lanjut (Action Plan)</label>
                    <textarea wire:model="action_taken" rows="2" placeholder="Contoh: Diajukan perbaikan ke vendor..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 sm:text-sm"></textarea>
                    @error('action_taken') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            @endif
        </form>

        <x-slot name="footer">
            <x-taspen-button variant="secondary" wire:click="closeModal">Batal</x-taspen-button>
            <x-taspen-button variant="primary" type="submit" form="docForm" loadingTarget="store">Simpan Log</x-taspen-button>
        </x-slot>
    </x-taspen-modal>
</div>
