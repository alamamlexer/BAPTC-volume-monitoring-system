@extends('layouts.index-layout')
@section('page_title','Home')
@section('content')

<div class="row table-responsive">
    <div class="col-md-6">
    <div class="table">
        <h1>Trading Inflows</h1>
        <table>
            <thead>
                <tr>
                    <th>Address</th>
                    <th>Number of Cars</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped_inflows as $inflow)
                    <tr>
                        <td>{{ $inflow['address'] }}</td>
                        <td>{{ $inflow['vehicle_count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-6">
    <div class="table">
        <h1>Trading Outflows</h1>
        <table>
            <thead>
                <tr>
                    <th>Address</th>
                    <th>Number of Cars</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped_outflows as $outflow)
                    <tr>
                        <td>{{ $outflow['address'] }}</td>
                        <td>{{ $outflow['vehicle_count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>  
    </div>
        
@endsection