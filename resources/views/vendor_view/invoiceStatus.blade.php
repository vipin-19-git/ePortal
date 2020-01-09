@extends('layouts.suplier_master')

@section('content')
 
        <div class="page-header">
            <h3 class="page-title">
         Invoice Clearance Status (Monthly)
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoice Clearance Status (Monthly)</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Invoice Clearance Status (Monthly)</h4>
                          <div class="row" id="alert_msg">
                  <div class="col-sm-7">
                 </div>
              @if ($message = Session::get('success'))
              
              <div class="col-sm-5 alert alert-success" role="alert" >
                     {{ $message }}
                  </div>
              
                 @endif
                    @if ($message = Session::get('error'))
                          
                    <div class="col-sm-5 alert alert-danger" role="alert" >

                     {{ $message }}
                
                </div>
                    @endif
                    @if ($errors->any())
                  
                    <div class="col-sm-5 alert alert-danger" role="alert">
                    {{ $errors->first() }}
                  </div>
                 @endif
               </div>
                  <form  action="{{ route('vendor.get_invoice_status') }}" method="post" id="main_form">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Company Code</label>
                      <div class="col-sm-3">
                        <select class="form-control" id="c_code" name="c_code"  onchange="get_plant(this.value)">
                         <option value="">-Select Company Code--</option>
                      @foreach($companies as $company)
                    <option value="{{$company->CompanyCode}}" @if(old('c_code') == $company->CompanyCode) selected="selected" @endif @if(Session::get('ccode')==$company->CompanyCode) selected="selected" @endif> {{  $company->CompanyName.'('.$company->CompanyCode.')' }}</option>
                    @endforeach
                        </select>
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Plant Code</label>
                      <div class="col-sm-3">
                         <select class="form-control" id="p_code" name="p_code">
                          <option value="">--Select Plant Code--</option>
                             @if(Session::get('pcode'))
                             <option value="{{Session::get('pcode')}}" selected="selected">{{Session::get('pcode')}}</option>
                              @endif
                        </select>
                      </div>
                    </div>
               
                         <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Inv Date (From)</label>
                            <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control"  id="datepicker" name="start_date" value="{{ old('start_date') }}@if($stdt=Session::get('stdt')) {{ date('m/d/Y',strtotime($stdt)) }} @endif">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                 
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Inv Date (To)</label>
                    
                          <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control"  id="datepicker2"  name="end_date"  value="{{ old('end_date') }} @if($enddt=Session::get('enddt')) {{ date('m/d/Y',strtotime($enddt)) }} @endif">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    
                    </div>
                <input type="hidden" name="download_type" id="download_type" value="0">
                    <button type="submit" class="btn btn-gradient-primary mr-2" name="get_delvery_sch" value="Show">Show</button>
                     <button type="submit" class="btn btn-gradient-dark btn-icon-text" name="get_delvery_sch" value="Send Mail" style="float: right;">
                      <i class="mdi mdi-email btn-icon-prepend"></i>Send Mail</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
               @php
                $i=1
                @endphp

     @if($invoice_status=Session::get('invoice_status'))
         
      <div class="col-12 grid-margin">
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Invoice Clearance Details</h4>

                 <!--  <p class="page-description">Add class <code>.table-striped</code> for table</p> -->
                  <div class="row">
                    
                    <div class="table-responsive">
         
                     
                    <div class="btn-group" style="float: right;">
                      <button type="button" class="btn btn-info">Download</button>
                      <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                       
                        <a class="dropdown-item"  onclick ="download_data('csv')">CSV</a>
                        <a class="dropdown-item"  onclick="download_data('pdf')">PDF</a>
                        <a class="dropdown-item"  onclick="download_data('print')">PRINT</a>
                        
                      </div>
                    </div>
            
                      <table class="table  table-bordered" id="printTable">
                        <thead>
                          <tr>
                  <th>#</th>
                  <th>Vendor code</th>
                  <th>Vendor Name</th>
                  <th>Plant</th>
                  <th>Invoice no.</th>
                   <th>Invoice Date</th>
                  <th>GRN No.</th>
                  <th>GRN Line item</th>
                   <th>GRN Date</th>
                  <th>Part Code</th>
                  <th>Part Description</th>
                  <th>Quantity</th>
                  <th>UOM</th>
                  <th>QC Lot Num</th>
                  <th>QC Date</th>
                  <th>QC Accepted Qty</th>
                  <th>QC Rejected Qty</th>
                </tr>
                          @php
                          $cls="";
                          $i=1;
                          @endphp

                @foreach($invoice_status as $invoice)
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
                  <td>{{ $i++}}</td>
                  <td>{{ $invoice['vendCode'] }}</td>
                  <td>{{ $invoice['vendName'] }}</td>
                  <td>{{ $invoice['plant'] }}</td>
                  <td>{{ $invoice['invoiceNo']}}</td>
                  <td>{{ $invoice['invoiceDate'] }}</td>
                  <td>{{ $invoice['grnNo'] }}</td>
                  <td>{{ $invoice['grnLineItem'] }}</td>
                  <td>{{ $invoice['grnDate'] }}</td>
                  <td>{{ $invoice['partCode'] }}</td>
                  <td>{{ $invoice['partDescription'] }}</td>
                  <td>{{ $invoice['quantity'] }}</td>
                  <td>{{ $invoice['UOM'] }}</td>
                  <td>{{ $invoice['QcLotNum'] }}</td>
                  <td>{{ $invoice['QcDate'] }}</td>
                  <td>{{ $invoice['QcAcceptedQty'] }}</td> 
                  <td>{{ $invoice['QcRejectedQty'] }}</td>
                </tr>
                 @endforeach
                @if(count($invoice_status)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="9">No any data</td>
                 
                </tr>
               @endif
                          
                        </tbody>
                      </table>
                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif


@endsection
<script type="text/javascript">
  function download_data(type)
  {
    if(type!="print" && type!='')
    {
      $("#download_type").val(type);
     $("#main_form").submit();
    }
   if(type=="print")
    {
     var style= '<style type="text/css">table { page-break-inside:auto } tr { page-break-inside:avoid; page-break-after:auto } thead { display:table-header-group } tfoot { display:table-footer-group } </style>';
     var content = $("#printTable").html();
     var mywindow = window.open('');
      mywindow.document.write('<html><head><title>Invoice Clearance Details</title>');
      mywindow.document.write(style);
     mywindow.document.write('</head><body >');
     mywindow.document.write(content);
     mywindow.document.write('</body></html>');
     mywindow.document.close();
     mywindow.focus()
     mywindow.print();
     mywindow.close();
     return true;
   /* var divToPrint = document.getElementById('printTable');
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();*/
   }
}

</script>