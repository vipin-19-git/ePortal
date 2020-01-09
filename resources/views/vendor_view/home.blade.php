@extends('layouts.suplier_master')

@section('content')
 
 <style>  
      #loaders {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
 display: none;

  
}


      </style>
          <div class="page-header">
            <h3 class="page-title">
              Dashboard
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                
              <div class="card bg-gradient-danger border-0 text-white p-3">
                <a href="{{route('vendor.generated_invoice')}}" style="text-decoration: none;color:white">
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-currency-inr mdi-48px"></i>
                    <div class="ml-4">
                      <h2 class="mb-2">{{$total_invoice}}</h2>
                      <h4 class="mb-0" style=" white-space: nowrap;">Number Of Invoices</h4>
                    </div>
                  </div>
                </div>
                 </a>
              </div>
           
            </div>
            <div class="col-md-4 stretch-card grid-margin">
               
              <div class="card bg-gradient-info border-0 text-white p-3">
                <a href="{{route('vendor.delivery_delay_info')}}" style="text-decoration: none;color:white">
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi mdi-clock mdi-48px"></i>
                    <div class="ml-4">
                      <h2 class="mb-2">{{$total_delay}}</h2>
                      <h4 class="mb-0" style="    white-space: nowrap;"> Delivery Delay</h4>
                    </div>
                  </div>
                </div>
                </a>
              </div>
               
            </div>
            <div class="col-md-4 stretch-card grid-margin">
            
              <div class="card bg-gradient-success border-0 text-white p-3">
                  <a href="{{route('vendor.dispactched_po')}}" style="text-decoration: none;color:white">
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi mdi-cart mdi-48px"></i>
                    <div class="ml-4">
                      <h2 class="mb-2">{{$total_PO}}</h2>
                      <h4 class="mb-0" style=" white-space: nowrap;">Number of PO's</h4>
                    </div>
                  </div>
                </div>
                   </a>     
              </div>
      
            </div>
          </div>
                     <div class="row">
            <div class="col-md-12 d-flex align-items-stretch">
              <div class="row flex-grow-1 w-100">
                <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"> @php
                     $x=Date('m');

                  @endphp
              Schedule Qty Vs. Dispatch Qty ( @if( $x< 4)  {{date("Y")-1}}-{{ date("y") }}    @else {{date("Y")}}-{{ date("y")+1 }} @endif)</h4>
                  <canvas id="mybarChart" style="height:408px;"></canvas>
                </div>
              </div>
            </div>
          </div>
       </div>
     </div>
           <div class="row">
            <div class="col-md-12 d-flex align-items-stretch">
              <div class="row flex-grow-1 w-100">
                <div class="col-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Generate Barcode</h4>
                       <div class="pull-right">
                 <button type="button" class="btn btn-box-tool" onclick="load_recent_data()"><i class="mdi mdi-refresh"></i>
                </button>
               
              </div>
                 

         
                    <div class="form-group row">
                      <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Search By:</label>
                      <div class="col-sm-5">
                     <select class="form-control" id="serch_by" name="search_by" 
                     class="form-control">
                     <option value="">-Select-</option>
                    <option value="1">PO Number</option>
                     <option value="2">Company Code</option>
                      <option value="3">Plant Code</option>
                      <option value="4">Material</option>
                  </select>
                      </div>
                         <div class="col-sm-4">
                        <input type="text" class="form-control" id="serch_text"  name="serch_text"  onkeyup="search_schedule(this.value)">
                      </div>
                    </div>
      
                      <div class="table-responsive">
                        <table class="table">

                          <thead>
                            <tr>
                              <th>#</th>
                              <th style="white-space: nowrap;text-align: center;">PO Number</th>
                   <th style="white-space: nowrap;text-align: center;">Stock</th>
                   <th style="white-space: nowrap;text-align: center;">Company</th>
                   <th style="white-space: nowrap;text-align: center;">Plant</th>
                   <th style="white-space: nowrap;text-align: center;">Item Description</th>
                            </tr>
                          </thead>
                          <tbody style="height:225px;min-height:225px;"> 

                     <div class="circle-loader" id="loaders"></div>
                        
                    
              
                            @include('vendor_view.presult')
                          </tbody>
                        </table>
                      </div>
                            <div style="float: right">
            {!! $sch_items->render() !!}
          </div>
                    </div>
                  </div>
                </div>
             
                
              </div>
            </div>
          </div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

$(document).ready(function(){
  $.ajax({
    url: "{{route('vendor.chart_data')}}",
  method: "post",
  data: { "_token": "{{ csrf_token() }}"},
  dataType: "json",
    success: function(data) {
    
      var a = [];
      var b = [];
      var y = [];

      for(var i in data) {
        y.push( data[i].y);
        b.push(data[i].b);
        a.push(data[i].a);
      }

     var data = {
     labels: y,
      datasets: [{
      label: 'Dispatch Quantity',
      data: b,
       backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 2,
        
      fill: false
    }
    ,{
      label: 'Schedule Quantity',
      data: a,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 2,
      fill: false
    }]
  };
 var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,

        },
        scaleLabel: {
        display: true,
        labelString: 'Quantity'
      } 
      }],
       xAxes: [{
        ticks: {
          beginAtZero: true,

        },
        scaleLabel: {
        display: true,
        labelString: 'Month'
      } 
      ,
      categorySpacing: 1,
      barPercentage: 0.9,
       maxBarThickness: 100,
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };
  /*var data = {
    labels: ["2013", "2014", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [10, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: false
    }]
  };*/
 if ($("#mybarChart").length) {
    var barChartCanvas = $("#mybarChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      
      options: options
    });
  }
    }
  });
});

 function load_recent_data()
{
 
  $('#loaders').show();
  var url = "{{route('vendor.get_recently_schedule')}}";
 $.ajax({
   url: url,
  method: "post",
  data: { "_token": "{{ csrf_token() }}"},
  dataType: "html",
   
  success: function(data)
   {
    $("tbody").html('');
    $("tbody").html(data);
    
   },
   complete: function(){
        $('#loaders').hide();
      }
}); 
} 
function search_schedule(serch_text)
{
  var output="";
var search_by=$("#serch_by").val();
if(search_by=="")
{
  alert("Select search by first");
}
else
{
  var po_url="{{ route('vendor.qrcode') }}";
  var url = "{{route('vendor.search_sch_data')}}";
 $.ajax({  
   url: url,
  method: "post",
  data: { "_token": "{{ csrf_token() }}",serch_text:serch_text,search_by:search_by},
  dataType: "html",
   
  success: function(data)
   {
   $("tbody").html('');
   $("tbody").html(data);

   }
}); 
}

}

</script>