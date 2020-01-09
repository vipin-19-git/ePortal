@extends('layouts.admin_master')

@section('content')
 
 
          <div class="page-header">
            <h3 class="page-title">
          Hold Registration
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hold Registration</li>
              </ol>
            </nav>
          </div>

             <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"> Hold Registration Details</h4>
                 
                  <div class="row">
                    <div class="table-responsive" id="printTable">
                      <div class="btn-group" style="float: right;" id="download_div">
                      <button type="button" class="btn btn-info">Download</button>
                      <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                        <a class="dropdown-item"  onclick ="download_data('excel')">EXCEL</a>
                        <a class="dropdown-item"  onclick ="download_data('csv')">CSV</a>
                        <a class="dropdown-item"  onclick="download_data('pdf')">PDF</a>
                        <a class="dropdown-item"  onclick="download_data('print')">PRINT</a>
                        
                      </div>
                    </div>
                       <form action="{{route('admin.exprt_hold_New_Vndr')}}" method="post" id="main_form">
                    <input type="hidden" id="export_type" name="export_type">
                       @csrf
                     </form>
                      <table class="table  table-bordered" >
                        <thead>
                         <tr>
                           <th>#</th>
                           <th>Vendor Name</th>
                           <th>Business Type</th>
                           <th>Mobile</th>
                           <th>Email</th>
                           <th>Phone No.</th>
                           <th>Address</th>
                           <th>GST No.</th>
                           <th>Pan No.</th>
                         </tr>
                        </thead>
                        <tbody>
                         @php
                $i=1;
            $cntry="";$st="";$cty="";
               @endphp
        @forelse($vendors as $vendor )
      @if($vendor && $vendor->Country)
      @php $cntry=$vendor->Country->country_name;  @endphp
      @endif
       @if($vendor && $vendor->State)
      @php  $st=$vendor->State->state_name;  @endphp
      @endif
      @if($vendor && $vendor->City)
     @php  $cty=$vendor->City->city_name;  @endphp
      @endif
               
                 @if($i%4==0)
                 @php $cls="table-info"; @endphp
                 @elseif($i%4==1)
                  @php  $cls="table-warning"; @endphp
                  @elseif($i%4==2)
                   @php  $cls="table-success"; @endphp
                   @elseif($i%4==3)
                   @php  $cls="table-primary"; @endphp
                   @endif
                
                   <tr class="{{ $cls }}">
                    <td>{{$i++}}</td>
          <td>{{$vendor->vendor_name}}</td>
          <td>@if($vendor && $vendor->BusinessType) {{ $vendor->BusinessType->name }} @endif</td>
           <td>{{$vendor->mobile}}</td>
           <td>{{ $vendor->email}}</td>
           <td>{{ $vendor->phone_no}}</td>
           <td>
           @if( $vendor->address_1!= "")
          {{ $vendor->address_1."," }}
          @endif

          @if( $vendor->address_2!= "")
          {{ $vendor->address_2."," }}
          @endif

          @if( $vendor->address_3!= "")
          {{ $vendor->address_1."," }}
          @endif

          @if($cty!= "")
          {{ $cty."," }}
          @endif
        @if($st!= "")
          {{ $st."," }}
          @endif
          @if( $cntry!= "")
          {{ $cntry }}
          @endif

       </td>
          <td>{{ $vendor->gst_no}}</td>
          <td>{{ $vendor->pan_no}}</td>

                 
               </tr>
               
         @empty
         <tr >
    
            <td style="text-align: center;color: red" colspan="6">No any data</td>
         </tr>
          @endforelse
                          
                        </tbody>
                      </table>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
  function download_data(type)
  {
    if(type!="print" && type!='')
    {
      $("#export_type").val(type);
     $("#main_form").submit();
    }
   if(type=="print")
    {
     var style= '<style type="text/css">table { page-break-inside:auto; width:100%} tr { page-break-inside:avoid; page-break-after:auto } thead { display:table-header-group } tfoot { display:table-footer-group } #download_div { display: none;} </style>';
     var content = $("#printTable").html();

     var mywindow = window.open('');
      mywindow.document.write('<html><head><title>Open vendor Query</title>');
      mywindow.document.write(style);
     mywindow.document.write('</head><body >');
     mywindow.document.write(content);
     mywindow.document.write('</body></html>');
     mywindow.document.close();
     mywindow.focus()
     mywindow.print();
     mywindow.close();
     return true;
  
   }
}

</script>