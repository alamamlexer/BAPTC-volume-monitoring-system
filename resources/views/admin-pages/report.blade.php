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
                <td class="text-end">0</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">-Cold Storage no.8 (Only functional cold storage)</td>
                <td class="text-end">0</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">Carrot Washing Facility</td>
                <td class="text-end">0</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-end">Inter Trading</td>
                <td class="text-end">0</td>
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
    

<!-- TABLE 5 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>V. NUMBERS OF ACCREDITTED/PROFILED STAKEHOLDERS FOR THE MONTH OF _____ 2024</b></h5>
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
                    <td class="text-center">FARMER GROUP</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">FARMER (Individual)</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">BUYER (Individual)</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">MARKET FACILITATOR</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">PACKER/PORTER/ASHER</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">PACKER,PORTER & WASHER'S GROUP</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-end">TOTAL</td>
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
    
<!-- TABLE 7 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>VII. MONITORED PRODUCTION SOURCE OF TRADED COMMODITES PER RANK</b></h5>
      <div class="table-responsive">
     
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
                <tr>
                    <td class="text-center">Commodity name</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">Commodity name</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">Commodity name</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">Commodity name</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">Commodity name</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-center">Commodity name</td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <th class="text-center">TOTAL WEIGHT IN KG</th>
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
                        <td class="text-center">TOTAL</td>
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
                        <td class="text-center">TOTAL</td>
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
    


<!-- TABLE 13 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>XIII. STATUS OF VEGETABLE TRADING STALLS</b></h5>
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
    

<!-- TABLE 14 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>XIV. MARKET LINKAGES</b></h5>
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
    


<!-- TABLE 15 -->
<div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">
        <h5 class="card-title"><b>XV. PRICE MONITORING</b></h5>

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
