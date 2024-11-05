@extends('layouts.admin')
@section('page_title', 'Reports')
@section('content')

    <!-- Page Title -->
    <div class="pagetitle">
        <h1>Reports</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">Reports</a></li>
            </ol>
        </nav>
    </div>


<section class="section dashboard">
<div class="row mb-3">
                      <form method="GET" action="{{ route('report.index') }}" class="mb-3">
                        <div class="input-group">
                        <div class="col-md-2">
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                        </div>
                        <div class="col-md-2">
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                        </div>
                            <button class="btn btn-primary" type="submit">Filter</button>
                      
                        </div>
                    </form>
                    </div>
  <!-- TABLE 1 -->
            <div class="row">
            <div class="col-lg-12">
            <div class="card">
<div class="card-body">
 <h5 class="card-title"><b>I. SUMMARIZED MONITORED VEGETABLE TRADING TRANSACTIONS</b></h5>
 
 <div class="table-responsive">
 <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2" class="text-center align-middle ">PARTICULAR</th>
                <th colspan="2" class="text-center">TOTAL VOLUME (KG)</th>

            </tr>
            <tr>
                <th class="text-center">INFLOW (KG)</th>
                <th class="text-center">OUTFLOW (KG)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>AM TRADING (Farmer)</td>
                <td class="text-center">{{ $table_one_data['AM_TRADING']['inflow'] ?? 0 }}</td>
                <td></td>
            </tr>
            <tr>
                <td>PM TRADING (Farmer)</td>
                <td class="text-center">{{ $table_one_data['PM_TRADING']['inflow'] ?? 0 }}</td>
                <td></td>
            </tr>
            <tr>
                <td>SHORT TRIP IN</td>
                <td class="text-center">{{ $table_one_data['SHORT_TRIP_IN']['inflow'] ?? 0 }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">-Dry Storage (includes the cold storage converted intro dry storage)</td>
                <td class="text-end">{{ $table_one_data['DRY']['dry'] ?? 0 }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">-Cold Storage no.8 (Only functional cold storage)</td>
                <td class="text-end">{{ $table_one_data['COLD']['cold'] ?? 0 }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">Carrot Washing Facility</td>
                <td class="text-end">{{ $table_one_data['WASHING']['washing'] ?? 0 }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">Inter Trading</td>
                <td class="text-end">{{ $table_one_data['INTER_TRADING']['intertrading'] ?? 0 }}</td>
                <td></td>
            </tr>

            <tr>
                <td>SHORT TRIP OUT</td>
                <td></td>
                <td class="text-center">{{ $table_one_data['SHORT_TRIP_OUT']['outflow'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>AM TRUCKINGS (Trader)</td>
                <td></td>
                <td class="text-center">{{ $table_one_data['AM_TRUCKINGS']['outflow'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>PM TRUCKINGS (Trader)</td>
                <td></td>
                <td class="text-center">{{ $table_one_data['PM_TRUCKINGS']['outflow'] ?? 0 }}</td>
            </tr>
            <tr>
                <td class="text-center fw-bold">GRAND TOTAL:</td>
                <td class="text-center fw-bold">{{ $table_one_data['GRAND_TOTAL_INFLOW']['all']?? 0 }}</td>
                <td class="text-center fw-bold">{{ $table_one_data['GRAND_TOTAL_OUTFLOW']['all']?? 0 }}</td>
            </tr>
        </tbody>
    </table>
 </div>
    
</div>
   

            </div>
            </div>
            </div>


<!-- TABLE 2 -->

<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>II. PEAK AND LEAN OF VEGETABLE TRADING TRANSACTIONS</b></h5>
      <div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-md-8 text-center">PARTICULAR</th>
            <th class="col-md-4 text-center">VALUE</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">Date of Peak Transactions</td>
            <td class="text-center">{{ $R2_peakDayDate }}</td>
        </tr>
        <tr>
            <td class="text-center">Volume of Traded Commodities During Peak Day (kgs)</td>
            <td class="text-center">{{ $R2_peakDay->total_volume ?? 0 }}</td>
        </tr>
        <tr>
            <td class="text-center">Date of Lean Transactions</td>
            <td class="text-center">{{ $R2_leanDayDate }}</td>
        </tr>
        <tr>
            <td class="text-center">Volume of Traded Commodities During Lean Day (kgs)</td>
            <td class="text-center">{{ $R2_leanDay->total_volume ?? 0 }}</td>
        </tr>
    </tbody>
</table>
      </div>
    </div>
    </div>
    </div>
    </div>




<!-- TABLE 3 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>III. DAILY AVERAGE WEIGHT (KG) and LOADING/DELIVERY FREQUENCY</b></h5>
      <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="col-md-4 text-center">PARTICULAR</th>
                    <th class="col-md-4 text-center">Daily Average Weight (KG)</th>
                    <th class="col-md-4 text-center">Daily Average Loading/Delivery Frequency</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">TRADER</td>
                    <td class="text-center">{{$table_three_data['trader']??0}}</td>
                    <td class="text-center">{{$table_three_data['trader_count']??0}}</td>
                </tr>
                <tr>
                    <td class="text-center">FARMER</td>
                    <td class="text-center">{{$table_three_data['farmer']??0}}</td>
                    <td class="text-center">{{$table_three_data['farmer_count']??0}}</td>
                </tr>
                <tr>
                    <td class="text-center border-end-0" >SHORT TRIP</td>
                    <td class="text-start border-0 "></td>
                    <td class="text-start border-start-0"></td>
                </tr>
                <tr>
                    <td class="text-end">IN</td>
                    <td class="text-center">{{$table_three_data['short_trip_in']??0}}</td>
                    <td class="text-center">{{$table_three_data['short_trip_in_count']??0}}</td>
                </tr>
                <tr>
                    <td class="text-end">OUT</td>
                    <td class="text-center">{{$table_three_data['short_trip_out']??0}}</td>
                    <td class="text-center">{{$table_three_data['short_trip_out_count']??0}}</td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
    </div>
    </div>
    </div>
    


<!-- TABLE 4 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>IV. MONITORED NUMBERS OF STAKEHOLDERS TRADED IN THE CENTER (FARMERS & BUYERS)</b></h5>
      <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-md-4 text-center">PARTICULAR</th>
                    <th class="col-md-4 text-center">NUMBER OF STAKEHOLDERS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">TRADER</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">FARMER</td>
                    <td class="text-center"></td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
    </div>
    </div>
    </div>
    


    



<!-- TABLE 6 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>VI. MONITORED COMMODITY VOLUME PER RANK WITH ESTIMATED MONETARY VALUE</b></h5>
      <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">COMMODITY</th>
                    <th class="text-center">PRODUCTION SOURCE</th>
                    <th class="text-center">VOLUME (KG)</th>
                    <th class="text-center">BAPTC AVERAGE PRICE (PHP)</th>
                    <th class="text-center">MONETARY VALUE (PHP)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_six_commodities as $index => $commodity)
                    <tr>
                        <td class="text-center" rowspan="{{ $commodity['transactions']->count() + 1 }}">
                            {{ $loop->iteration }}
                        </td>
                        <td class="text-center" rowspan="{{ $commodity['transactions']->count() + 1 }}">
                            {{ $commodity['commodity_name'] }}
                        </td>
                    </tr>
        
                    @foreach ($commodity['transactions'] as $transaction)
                        <tr>
                            <td class="text-center">{{ $transaction['municipality'] }}</td>
                            <td class="text-center">{{ $transaction['total_volume'] }}</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                    @endforeach
        
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-end" colspan="2">Sub-total</td>
                        <td class="text-center">{{ $commodity['total_volume'] }}</td>
                    </tr>
                @endforeach
                
                <tr>
                    <td class="text-center fw-bold" colspan="3">GRAND TOTAL</td>
                    <td class="text-center fw-bold">{{ $table_six_grand_total_volume }}</td>
                    <td class="text-center fw-bold"></td>
                    <td class="text-center fw-bold"></td>
                </tr>
            </tbody>
        </table>
        
        
      </div>
    </div>
    </div>
    </div>
    </div>
    
<!-- Table 7-->
    <!-- TABLE 7 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><b>VII. DELIVERY VOLUME AND FREQUENCY PER MUNICIPALITY</b></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">MUNICIPALITY</th>
                                    <th class="text-center">COMMODITY</th>
                                    <th class="text-center">DELIVERY VOLUME (KG)</th>
                                    <th class="text-center">DELIVERY FREQUENCY</th>
                                    <th class="text-center">PERCENTAGE SHARE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($transactionsByMunicipality as $municipality => $commodities)
                                @foreach($commodities as $data)
                                @if ($loop->first)
                                <tr>
                                    <td class="text-center">{{ $counter }}</td>
                                    <td class="text-center" rowspan="{{ count($commodities) }}">{{ $municipality }}</td>
                                    <td class="text-center">{{ $data['commodity']->commodity_name }}</td>
                                    <td class="text-center">{{ $data['total_volume'] }}</td>
                                    <td class="text-center">{{ $data['delivery_frequency'] }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center">{{ $data['commodity']->commodity_name }}</td>
                                    <td class="text-center">{{ $data['total_volume'] }}</td>
                                    <td class="text-center">{{ $data['delivery_frequency'] }}</td>
                                </tr>
                                @endif
                                @endforeach

                                <!-- Subtotal Row -->
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-end" colspan="2"><strong>Subtotal</strong></td>
                                    <td class="text-center"><strong>{{ $subtotals[$municipality]['subtotal_volume'] }}</strong></td>
                                    <td class="text-center"><strong>{{ $subtotals[$municipality]['subtotal_frequency'] }}</strong></td>
                                    <td class="text-center"><strong>{{ number_format(($subtotals[$municipality]['subtotal_volume'] / $grandTotalVolume) * 100, 2) }}%</strong></td>
                                </tr>

                                @php $counter++; @endphp
                                @endforeach

                                <!-- Grand Total Row -->
                                <tr>
                                    <td class="text-center fw-bold" colspan="3">GRAND TOTAL</td>
                                    <td class="text-center fw-bold">{{ $grandTotalVolume }}</td>
                                    <td class="text-center fw-bold">{{ $grandTotalFrequency }}</td>
                                    <td class="text-center fw-bold">
                                        <strong>{{ number_format($totalGrandPercentage, 2) }}%</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    

<!-- TABLE 8 -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><b>VIII. MONITORED MARKET DESTINATION OF BROUGHT COMMODITIES</b></h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">PROVINCE</th>
                                <th class="text-center">VOLUME IN KG</th>
                                <th class="text-center">LOADING FREQUENCY</th>
                                <th class="text-center">PERCENTAGE SHARE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1; @endphp
                            @foreach ($table_eight_data as $data)
                                <tr>
                                    <td class="text-center">{{ $index++ }}</td>
                                    <td class="text-center">{{ $data['destination'] }}</td>
                                    <td class="text-center">{{ $data['volume'] }}</td>
                                    <td class="text-center">{{ $data['frequency'] }}</td>
                                    <td class="text-center">{{ $data['percentage_share'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="2"><strong>GRAND TOTAL</strong></td>
                                <td class="text-center"><strong>{{ $formattedGrandTotalVolume }}</strong></td>
                                <td class="text-center"><strong>{{ $formattedGrandTotalFrequency }}</strong></td>
                                <td class="text-center"><strong>100.00%</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    


<!-- TABLE 9 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>IX. MONITORED VOLUME OF COMMODITIES AT THE CARROT WASHING FACILITY</b></h5>
      <div class="table-responsive">
        <table class="table mx-auto">
            <thead>
                <tr>
                    <th class="col-md-4 text-center">COMMODITY</th>
                    <th class="col-md-4 text-center">WEIGHT IN KG</th>
                </tr>
            </thead>
            <tbody>
                
            @foreach ($washingTransactions as $transaction)
            <tr>
                <td class="col-md-4 text-center">{{ $transaction['commodity']->commodity_name }}</td>
                <td class="col-md-4 text-center">{{ $transaction['volume'] }}</td>
            </tr>
            @endforeach
                
        <tr>
            <td class="col-md-4 text-center"><strong>Total Volume</strong></td>
            <td class="col-md-4 text-center"><strong>{{ $totalVolume }}</strong></td>
        </tr>
            </tbody>
        </table>
      </div>
    </div>
    </div>
    </div>
    </div>
    


<!-- TABLE 10 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>X. MONITORED VOLUME OF TRADED ASSORTED COMMODITIES IN THE DRY AND COLD STORAGE FACILITY</b></h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-md-4 text-center">DRY STORAGE NO.</th>
                        <th class="col-md-4 text-center">COMMODITY</th>
                        <th class="col-md-4 text-center">VOLUME (KG)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Assorted Commodities</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Assorted Commodities</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-end">Sub-total.</th>
                        <td class="col-md-4 text-center"><strong>TOTAL</td>
                    </tr>
                </tbody>
            
            
                <thead>
                    <tr>
                        <th class="col-md-4 text-center">COLD STORAGE as DRY STORAGE</th>
                        <th class="col-md-4 text-center">COMMODITY</th>
                        <th class="col-md-4 text-center">VOLUME (KG)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Commodity Name</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Commodity Name</td>
                        <td class="text-center"></td>
                    </tr> <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Commodity Name</td>
                        <td class="text-center"></td>
                    </tr> <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Commodity Name</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-end">Sub-total.</th>
                        <td class="col-md-4 text-center"><strong>TOTAL</td>
                    </tr>
                </tbody>
            
            
                <thead>
                    <tr>
                        <th class="col-md-4 text-center">COLD STORAGE (Functional)</th>
                        <th class="col-md-4 text-center">COMMODITY</th>
                        <th class="col-md-4 text-center">VOLUME (KG)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Assorted Commodities</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td class="text-center">No.</td>
                        <td class="text-center">Assorted Commodities</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-end">Sub-total.</th>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-end">Grant total.</th>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
            </table>
      </div>
    </div>
    </div>
    </div>
    </div>



<!-- TABLE 11 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>XI. MONITORED TRADING TRANSACTION FROM THE INTER TRADING</b></h5>
        <div class="table-responsive">
       <table class="table">
      <thead>
            <tr>
                <th class="col-md-4 text-center">COMMODITY</th>
                <th class="col-md-4 text-center">VOLUME (KG)</th>
                <th class="col-md-4 text-center">PRODUCTION SOURCE</th>
            </tr>
      </thead>
      <tbody>
        @foreach ($intertradingTransactions as $transaction)
            <tr>
                <td class="col-md-4 text-center">{{ $transaction['commodity']->commodity_name }}</td>
                <td class="col-md-4 text-center">{{ $transaction['volume'] }}</td>
                <td class="col-md-4 text-center">{{ $transaction['province'] }}</td>
            </tr>
        @endforeach  
            <tr>
                <td class="col-md-4 text-center"><strong>Grand Total (KG)</strong></td>
                <th colspan="2" class="text-center">{{ $totalVolume }}</th>
            </tr>
      </tbody>
      </table>  
      </div>
    </div>
    </div>
    </div>
    </div>
    


<!-- TABLE 12 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>XII. COMPARISON BETWEEN THE MONITORED VEGETABLE VOLUME IN MAY 2023 AND MAY 2024</b></h5>
        <div class="table-responsive">
      {{-- <table class="table">Place the table here and add a class="table"
      <thead>
      </thead>
      <tbody>
      </tbody>
      </table>  --}}
      </div>
    </div>
    </div>
    </div>
    </div>
    




</section>
  



@endsection
