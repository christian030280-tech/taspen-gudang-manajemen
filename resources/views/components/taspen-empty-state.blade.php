@props(['icon' => 'inbox', 'title' => 'Data Kosong', 'description' => 'Belum ada data yang tercatat.'])

<div class="flex flex-col items-center justify-center p-8 text-center w-full">
    <div class="p-3 bg-gray-50 rounded-full mb-4">
        <x-dynamic-component :component="'lucide-' . $icon" class="w-8 h-8 text-gray-400" />
    </div>
    <h3 class="text-sm font-semibold text-gray-800 mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-4">{{ $description }}</p>
    
    @if(isset($action))
        {{ $action }}
    @endif
</div>
