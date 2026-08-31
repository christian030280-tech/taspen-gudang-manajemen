@props(['type' => 'button', 'variant' => 'primary', 'icon' => null, 'loadingTarget' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-[#1557A6] text-white hover:bg-blue-800 focus:ring-[#1557A6]',
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-gray-200',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'ghost' => 'text-gray-600 bg-transparent shadow-none hover:bg-gray-100 hover:text-gray-900 focus:ring-gray-200',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} 
    @if($loadingTarget)
        wire:loading.attr="disabled" wire:target="{{ $loadingTarget }}"
    @endif
>
    @if($icon && empty($loadingTarget))
        <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 mr-2" />
    @endif

    @if($loadingTarget)
        <x-lucide-loader class="w-4 h-4 mr-2 animate-spin hidden" wire:loading.class.remove="hidden" wire:target="{{ $loadingTarget }}" />
        <span wire:loading.class="hidden" wire:target="{{ $loadingTarget }}">
            @if($icon)
                <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 mr-2 inline-block" />
            @endif
        </span>
    @endif

    {{ $slot }}
</button>
