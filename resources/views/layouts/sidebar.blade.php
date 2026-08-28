<aside class="flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-white border-r border-gray-200 shadow-sm">
    <div class="flex items-center justify-center mb-6">
        <h2 class="text-2xl font-bold text-taspen-blue">TASPEN</h2>
    </div>
    <div class="flex items-center justify-center mb-8">
        <span class="text-sm font-semibold text-gray-500">Sistem Gudang</span>
    </div>

    <div class="flex flex-col justify-between flex-1">
        <nav>
            <a class="flex items-center px-4 py-2 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue {{ request()->routeIs('dashboard') ? 'bg-taspen-light-blue text-taspen-blue' : '' }}" href="{{ route('dashboard') }}">
                <x-lucide-layout-dashboard class="w-5 h-5" />
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            <div class="mt-6 text-xs font-semibold text-gray-400 uppercase mb-2 px-4">PENGELOLAAN</div>
            
            <!-- Persediaan -->
            <div x-data="{ open: {{ request()->routeIs('items.*') || request()->routeIs('transactions.*') || request()->routeIs('categories.*') || request()->routeIs('units.*') || request()->routeIs('locations.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 mt-1 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue">
                    <div class="flex items-center">
                        <x-lucide-box class="w-5 h-5" />
                        <span class="mx-4 font-medium">Persediaan</span>
                    </div>
                    <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" />
                </button>
                <div x-show="open" class="mt-1 space-y-1 px-4" x-transition>
                    <a href="{{ route('items.index') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('items.*') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Data Barang</a>
                    <a href="{{ route('transactions.in') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('transactions.in') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Barang Masuk</a>
                    <a href="{{ route('transactions.out') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('transactions.out') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Barang Keluar</a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="{{ route('categories.index') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('categories.*') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Kategori</a>
                    <a href="{{ route('units.index') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('units.*') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Satuan</a>
                    <a href="{{ route('locations.index') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('locations.*') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Lokasi</a>
                </div>
            </div>

            <!-- Inventaris / Aset -->
            <div x-data="{ open: {{ request()->routeIs('assets.*') ? 'true' : 'false' }} }" class="mt-1">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue">
                    <div class="flex items-center">
                        <x-lucide-monitor-speaker class="w-5 h-5" />
                        <span class="mx-4 font-medium">Inventaris / Aset</span>
                    </div>
                    <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" />
                </button>
                <div x-show="open" class="mt-1 space-y-1 px-4" x-transition>
                    <a href="{{ route('assets.index') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('assets.*') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Data Aset</a>
                </div>
            </div>

            <!-- Peminjaman -->
            <div x-data="{ open: {{ request()->routeIs('borrowings.*') ? 'true' : 'false' }} }" class="mt-1">
                <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue">
                    <div class="flex items-center">
                        <x-lucide-arrow-right-left class="w-5 h-5" />
                        <span class="mx-4 font-medium">Peminjaman</span>
                    </div>
                    <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" />
                </button>
                <div x-show="open" class="mt-1 space-y-1 px-4" x-transition>
                    <a href="{{ route('borrowings.index') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-taspen-blue hover:bg-taspen-light-blue rounded-md {{ request()->routeIs('borrowings.*') ? 'text-taspen-blue bg-taspen-light-blue' : '' }}">Data Peminjaman</a>
                </div>
            </div>

            <!-- Stock Opname -->
            <a class="flex items-center px-4 py-2 mt-1 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue {{ request()->routeIs('stock-opnames.*') ? 'bg-taspen-light-blue text-taspen-blue' : '' }}" href="{{ route('stock-opnames.index') }}">
                <x-lucide-clipboard-check class="w-5 h-5" />
                <span class="mx-4 font-medium">Stock Opname</span>
            </a>

            <div class="mt-6 text-xs font-semibold text-gray-400 uppercase mb-2 px-4">LAPORAN</div>
            <a class="flex items-center px-4 py-2 mt-1 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue {{ request()->routeIs('reports.*') ? 'bg-taspen-light-blue text-taspen-blue' : '' }}" href="{{ route('reports.index') }}">
                <x-lucide-file-bar-chart-2 class="w-5 h-5" />
                <span class="mx-4 font-medium">Laporan TASPEN</span>
            </a>
            
            <div class="mt-6 text-xs font-semibold text-gray-400 uppercase mb-2 px-4">PENGATURAN</div>
            <a class="flex items-center px-4 py-2 mt-1 text-gray-600 transition-colors rounded-lg hover:bg-taspen-light-blue hover:text-taspen-blue" href="#">
                <x-lucide-users class="w-5 h-5" />
                <span class="mx-4 font-medium">User</span>
            </a>
        </nav>
    </div>
</aside>
