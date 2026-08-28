<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6">
        <!-- Total Persediaan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-taspen-light-blue text-taspen-blue">
                <x-lucide-box class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Persediaan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalItems }} <span class="text-xs font-normal text-gray-400">jenis</span></p>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-taspen-light-blue text-taspen-blue">
                <x-lucide-layers class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Stok</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalStock }}</p>
            </div>
        </div>

        <!-- Total Aset -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-taspen-light-blue text-taspen-blue">
                <x-lucide-monitor-speaker class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Aset</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAset }}</p>
            </div>
        </div>

        <!-- Aset Rusak -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-red-50 text-taspen-danger">
                <x-lucide-alert-triangle class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Aset Rusak</p>
                <p class="text-2xl font-bold text-gray-800">{{ $asetRusak }}</p>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-yellow-50 text-taspen-warning">
                <x-lucide-alert-circle class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                <p class="text-2xl font-bold text-gray-800">{{ $lowStockItems->count() }}</p>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-orange-50 text-taspen-orange">
                <x-lucide-arrow-right-left class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Dipinjam</p>
                <p class="text-2xl font-bold text-gray-800">{{ $sedangDipinjam }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Barang Masuk vs Keluar</h3>
            <!-- Assuming Chart.js is used, or basic CSS bars. Let's use simple CSS bars as requested by "Chart tidak perlu berlebihan" -->
            <div class="space-y-4">
                @foreach($chartLabels as $index => $label)
                <div>
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>{{ $label }}</span>
                    </div>
                    <div class="flex space-x-2 h-4">
                        <div class="bg-taspen-blue rounded" style="width: {{ $chartDataIn[$index] > 0 ? max(($chartDataIn[$index] / (max($chartDataIn) ?: 1)) * 100, 5) : 0 }}%; min-width: {{ $chartDataIn[$index] > 0 ? '10px' : '0' }}"></div>
                        <div class="bg-taspen-yellow rounded" style="width: {{ $chartDataOut[$index] > 0 ? max(($chartDataOut[$index] / (max($chartDataOut) ?: 1)) * 100, 5) : 0 }}%; min-width: {{ $chartDataOut[$index] > 0 ? '10px' : '0' }}"></div>
                    </div>
                    @if($chartDataIn[$index] > 0 || $chartDataOut[$index] > 0)
                    <div class="flex space-x-4 text-xs text-gray-400 mt-1">
                        @if($chartDataIn[$index] > 0)<span class="text-taspen-blue">Masuk: {{ $chartDataIn[$index] }}</span>@endif
                        @if($chartDataOut[$index] > 0)<span class="text-taspen-yellow">Keluar: {{ $chartDataOut[$index] }}</span>@endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="flex items-center space-x-4 mt-6 text-sm text-gray-600">
                <div class="flex items-center"><div class="w-3 h-3 bg-taspen-blue rounded mr-2"></div> Barang Masuk</div>
                <div class="flex items-center"><div class="w-3 h-3 bg-taspen-yellow rounded mr-2"></div> Barang Keluar</div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Low Stock Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Stok Menipis</h3>
                @if($lowStockItems->count() > 0)
                <div class="space-y-4">
                    @foreach($lowStockItems as $item)
                    <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->code }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-taspen-danger">
                                {{ $item->current_stock }} {{ $item->unit->short_name ?? $item->unit->name ?? 'PCS' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-gray-500 text-sm">
                    Stok barang aman.
                </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 rounded-full bg-taspen-blue mt-1.5"></div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ optional($activity->user)->name ?? 'System' }}</p>
                            <p class="text-sm text-gray-600">{{ $activity->activity }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-gray-500 text-sm">
                    Belum ada aktivitas.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
