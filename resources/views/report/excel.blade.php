<table>
    <tr>
        <td colspan="5" style="font-weight: bold; font-size: 14pt;">PT TASPEN (PERSERO)</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight: bold; font-size: 16pt;">{{ $title }}</td>
    </tr>
    @foreach($filters as $k => $v)
    <tr>
        <td colspan="5">{{ $k }}: {{ $v }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="5">Dicetak pada: {{ now()->format('d M Y H:i') }}</td>
    </tr>
    <tr></tr>
</table>

@include('livewire.report.partials.table-master', ['data' => $data, 'report_type' => $report_type])
