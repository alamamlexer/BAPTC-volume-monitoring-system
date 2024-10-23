@extends('layouts.index-layout')
@section('page_title','Home')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h1>Trading Inflows</h1>
        <table>
            <thead>
                <tr>
                    <th>Municipality</th>
                    <th>Number of Cars</th>
                </tr>
            </thead>
            <tbody>
                @if ($grouped_inflows->isEmpty())
                    <tr>
                        <td colspan="2" class="text-center">No trading inflows found.</td>
                    </tr>
                @else
                @foreach ($grouped_inflows as $inflow)
                    <tr>
                        <td>{{ $inflow['municipality'] }}</td>
                        <td>{{ $inflow['vehicle_count'] }}</td>
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h1>Trading Outflows</h1>
        <table>
            <thead>
                <tr>
                    <th>Municipality</th>
                    <th>Number of Cars</th>
                </tr>
            </thead>
            <tbody>
                 @if ($grouped_outflows->isEmpty())
                    <tr>
                        <td colspan="2" class="text-center">No trading outflows found.</td>
                    </tr>
                @else
                @foreach ($grouped_outflows as $outflow)
                    <tr>
                        <td>{{ $outflow['municipality'] }}</td>
                        <td>{{ $outflow['vehicle_count'] }}</td>
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection