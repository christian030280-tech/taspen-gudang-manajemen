<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Dokumen TASPEN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans p-8">
    <div class="max-w-5xl mx-auto">
        
        <div class="no-print mb-6 flex justify-end space-x-2">
            <button onclick="window.close()" class="px-6 py-2 border text-gray-600 font-bold rounded shadow hover:bg-gray-100">Tutup</button>
            <button onclick="window.print()" class="px-6 py-2 bg-[#1557A6] text-white font-bold rounded shadow hover:bg-blue-800">Print Dokumen</button>
        </div>

        <!-- Kop Surat TASPEN -->
        <div class="flex items-center border-b-4 border-[#1557A6] pb-4 mb-6">
            <div class="w-1/6 flex justify-center">
                <div class="w-16 h-16 bg-[#1557A6] text-white flex items-center justify-center font-bold text-3xl rounded shadow-sm">
                    T
                </div>
            </div>
            <div class="w-5/6 text-center">
                <h1 class="text-3xl font-extrabold text-[#1557A6] tracking-wide uppercase">PT TASPEN (PERSERO)</h1>
                <p class="text-md text-gray-700 font-bold uppercase mt-1">Sistem Manajemen Gudang & Inventaris Aset</p>
                <p class="text-sm text-gray-500 mt-1">Jl. Letjen Suprapto No.45, Cempaka Putih, Jakarta Pusat</p>
            </div>
        </div>

        <main>
            @yield('content')
        </main>

        <div class="mt-20 pt-8 border-t-2 border-gray-100">
            <div class="flex justify-between">
                <div class="text-sm text-gray-500">
                    Dicetak oleh: <span class="font-bold text-gray-800">{{ Auth::user()->name ?? 'System' }}</span><br>
                    Tanggal Cetak: <span class="font-bold text-gray-800">{{ now()->format('d F Y, H:i') }}</span>
                </div>
                <div class="text-center w-48">
                    <p class="text-sm text-gray-700 mb-20">Mengetahui,<br><strong>Kepala Gudang</strong></p>
                    <p class="text-sm font-bold text-gray-900 border-b border-gray-500 pb-1">........................................</p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
