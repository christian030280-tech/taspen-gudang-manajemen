@props(['isOpen' => false, 'title' => '', 'width' => 'sm:max-w-lg'])

@if($isOpen)
<div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-modal="true">
    <div class="bg-white rounded-xl shadow-xl w-full {{ $width }} mx-4 max-h-[90vh] overflow-y-auto transform transition-all relative">
        
        <!-- Loading Overlay if needed (passed via slot or logic) -->

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white sticky top-0 z-10">
            <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
            @if(isset($closeAction))
                <button {{ $closeAction }} class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            @endif
        </div>
        
        <!-- Content -->
        <div class="px-6 py-6">
            {{ $slot }}
        </div>
        
        <!-- Footer -->
        @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end space-x-2 sticky bottom-0 z-10">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
@endif
