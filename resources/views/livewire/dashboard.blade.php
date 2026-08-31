<div>
    <x-taspen-page-header title="Dashboard" description="Ringkasan kondisi gudang dan inventaris internal TASPEN.">
        <x-slot name="actions">
            <div class="flex space-x-2">
                <a href="{{ route('transactions.in') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#1557A6] text-white text-sm font-bold rounded-md shadow-sm hover:bg-blue-800 transition-colors">
                    <x-lucide-arrow-down-to-line class="w-4 h-4 mr-2" /> Barang Masuk
                </a>
                <a href="{{ route('transactions.out') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white text-[#1557A6] border border-[#1557A6] text-sm font-bold rounded-md shadow-sm hover:bg-blue-50 transition-colors">
                    <x-lucide-arrow-up-from-line class="w-4 h-4 mr-2" /> Barang Keluar
                </a>
            </div>
        </x-slot>
    </x-taspen-page-header>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <!-- Total Jenis Barang (Persediaan) -->
        <x-taspen-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-xl bg-blue-50 text-[#1557A6]">
                    <x-lucide-box class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Barang</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalItems }}</p>
                </div>
            </div>
        </x-taspen-card>

        <!-- Total Stok Persediaan -->
        <x-taspen-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-xl bg-green-50 text-green-600">
                    <x-lucide-layers class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalStock }}</p>
                </div>
            </div>
        </x-taspen-card>

        <!-- Total Aset -->
        <x-taspen-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                    <x-lucide-monitor-speaker class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Aset</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAset }}</p>
                </div>
            </div>
        </x-taspen-card>

        <!-- Stok Menipis -->
        <x-taspen-card class="hover:shadow-md transition-shadow border-t-4 border-t-yellow-400">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-xl bg-yellow-50 text-yellow-600">
                    <x-lucide-alert-circle class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Menipis</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $lowStockItems->count() }}</p>
                </div>
            </div>
        </x-taspen-card>

        <!-- Sedang Dipinjam -->
        <x-taspen-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-xl bg-orange-50 text-orange-500">
                    <x-lucide-arrow-right-left class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dipinjam</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $sedangDipinjam }}</p>
                </div>
            </div>
        </x-taspen-card>

        <!-- Aset Bermasalah / Rusak -->
        <x-taspen-card class="hover:shadow-md transition-shadow border-t-4 border-t-red-500">
            <div class="flex items-center space-x-3">
                <div class="p-3 rounded-xl bg-red-50 text-red-600">
                    <x-lucide-alert-triangle class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aset Rusak</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $asetRusak }}</p>
                </div>
            </div>
        </x-taspen-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2">
            <x-taspen-card title="Grafik Barang Masuk vs Keluar (7 Hari Terakhir)">
                <x-slot name="icon"><x-lucide-bar-chart-2 class="w-5 h-5 text-[#1557A6]" /></x-slot>
                
                @if(array_sum($chartDataIn) == 0 && array_sum($chartDataOut) == 0)
                    <div class="py-12 flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                        <x-lucide-bar-chart-3 class="w-12 h-12 text-gray-300 mb-3" />
                        <h4 class="text-gray-900 font-bold mb-1">Belum ada transaksi</h4>
                        <p class="text-gray-500 text-sm max-w-sm text-center">Belum terdapat transaksi barang masuk atau keluar pada periode ini.</p>
                        <a href="{{ route('transactions.in') }}" class="mt-4 text-sm font-bold text-[#1557A6] hover:underline">Catat Barang Masuk</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($chartLabels as $index => $label)
                        <div>
                            <div class="flex justify-between text-sm font-medium text-gray-700 mb-1.5">
                                <span>{{ $label }}</span>
                            </div>
                            <div class="flex h-5 rounded-md overflow-hidden bg-gray-100">
                                @if($chartDataIn[$index] > 0)
                                <div class="bg-[#1557A6] flex items-center justify-center text-[10px] text-white font-bold transition-all duration-500" style="width: {{ max(($chartDataIn[$index] / (max($chartDataIn) ?: 1)) * 100, 5) }}%;">
                                    {{ $chartDataIn[$index] }}
                                </div>
                                @endif
                                @if($chartDataOut[$index] > 0)
                                <div class="bg-yellow-400 flex items-center justify-center text-[10px] text-white font-bold transition-all duration-500" style="width: {{ max(($chartDataOut[$index] / (max($chartDataOut) ?: 1)) * 100, 5) }}%;">
                                    {{ $chartDataOut[$index] }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-center space-x-6 mt-8 text-sm font-medium text-gray-600 border-t border-gray-100 pt-5">
                        <div class="flex items-center"><div class="w-3 h-3 bg-[#1557A6] rounded mr-2 shadow-sm"></div> Barang Masuk</div>
                        <div class="flex items-center"><div class="w-3 h-3 bg-yellow-400 rounded mr-2 shadow-sm"></div> Barang Keluar</div>
                    </div>
                @endif
            </x-taspen-card>
        </div>

        <div class="space-y-6">
            <!-- Low Stock Table -->
            <x-taspen-card title="Stok Menipis">
                <x-slot name="icon"><x-lucide-alert-circle class="w-5 h-5 text-yellow-500" /></x-slot>
                
                @if($lowStockItems->count() > 0)
                <div class="space-y-3">
                    @foreach($lowStockItems as $item)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-red-100 bg-red-50/30">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->code }} • Min: {{ $item->minimum_stock }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-md">
                                {{ $item->current_stock }} {{ $item->unit->short_name ?? 'PCS' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('items.index') }}" class="text-xs font-bold text-[#1557A6] hover:underline">Lihat Semua Data Barang &rarr;</a>
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-6">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3">
                        <x-lucide-check class="w-6 h-6" />
                    </div>
                    <p class="text-gray-800 font-bold">Stok Aman</p>
                    <p class="text-gray-500 text-sm">Semua persediaan dalam kondisi cukup.</p>
                </div>
                @endif
            </x-taspen-card>

            <!-- Recent Activity -->
            <x-taspen-card title="Aktivitas Terbaru">
                <x-slot name="icon"><x-lucide-history class="w-5 h-5 text-[#1557A6]" /></x-slot>
                
                @if($recentActivities->count() > 0)
                <div class="relative pl-2">
                    <!-- Vertical Line -->
                    <div class="absolute left-[15px] top-2 bottom-2 w-px bg-gray-200"></div>
                    
                    <div class="space-y-5">
                        @foreach($recentActivities as $activity)
                        <div class="relative flex items-start group">
                            <!-- Timeline Dot -->
                            <div class="absolute left-[-2px] mt-1 w-3.5 h-3.5 rounded-full border-2 border-white {{ $activity->type === 'in' ? 'bg-green-500' : ($activity->type === 'out' ? 'bg-yellow-500' : 'bg-gray-400') }} shadow-sm z-10 group-hover:scale-125 transition-transform"></div>
                            
                            <div class="ml-8 w-full">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm font-bold text-gray-800">
                                        @if($activity->type === 'in')
                                            Barang Masuk
                                        @elseif($activity->type === 'out')
                                            Barang Keluar
                                        @else
                                            Penyesuaian
                                        @endif
                                    </p>
                                    <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    <span class="font-semibold text-[#1557A6]">{{ $activity->item->name ?? 'Item Terhapus' }}</span> 
                                    @if($activity->type === 'in')
                                        <span class="text-green-600 font-bold">+{{ $activity->quantity }}</span>
                                    @elseif($activity->type === 'out')
                                        <span class="text-red-600 font-bold">-{{ $activity->quantity }}</span>
                                    @else
                                        <span class="text-gray-600 font-bold">{{ $activity->quantity }}</span>
                                    @endif
                                    {{ optional($activity->item)->unit->short_name ?? '' }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1">Oleh: {{ optional($activity->user)->name ?? 'System' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 text-center border-t border-gray-50 pt-3">
                    <a href="{{ route('transactions.history') }}" class="text-xs font-bold text-[#1557A6] hover:underline">Lihat Histori Lengkap &rarr;</a>
                </div>
                @else
                <div class="text-center py-6">
                    <x-lucide-activity class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                    <p class="text-gray-500 text-sm">Belum ada aktivitas transaksi.</p>
                </div>
                @endif
            </x-taspen-card>
        </div>
    </div>
</div>
