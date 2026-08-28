@extends('layouts.print')

@section('content')

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 uppercase text-center mb-4">{{ $title }}</h2>
        
        @if(count($filters) > 0)
        <div class="bg-gray-50 border border-gray-200 p-4 rounded mb-6">
            <h4 class="font-bold text-sm text-gray-700 mb-2 uppercase border-b border-gray-200 pb-1">Parameter Laporan:</h4>
            <div class="grid grid-cols-2 gap-2 text-sm">
                @foreach($filters as $key => $value)
                <div><span class="font-bold text-gray-600">{{ $key }}:</span> {{ $value }}</div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- The table is the exact same one used in the Livewire view, but we can reuse the partial -->
    <div class="mb-8">
        @include('livewire.report.partials.table-master', ['data' => $data, 'report_type' => $report_type])
    </div>

@endsection
