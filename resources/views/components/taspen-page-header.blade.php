@props(['title', 'description' => null])

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $title }}</h2>
        @if($description)
            <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
