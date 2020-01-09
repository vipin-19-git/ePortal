@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
             Delivery Schedule
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delivery Schedule</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Delivery Schedule</h4>
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
                  <form  action="{{ route('admin.get_schedule') }}" method="post" id="main_form">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Company Code</label>
                      <div class="col-sm-3">
                        <select class="form-control mytxt" id="c_code" name="c_code"  onchange="get_plant(this.value)">
                         <option value="">-Select Company Code--</option>
                      @foreach($companies as $company)
                    <option value="{{$company->CompanyCode}}" @if(old('c_code') == $company->CompanyCode) selected="selected" @endif @if(Session::get('ccode')==$company->CompanyCode) selected="selected" @endif> {{  $company->CompanyName.'('.$company->CompanyCode.')' }}</option>
                    @endforeach
                        </select>
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Plant Code</label>
                      <div class="col-sm-3">
                         <select class="form-control mytxt" id="p_code" name="p_code">
                          <option value="">--Select Plant Code--</option>
                             
                             @if(Session::get('pcode'))
                             <option value="{{Session::get('pcode')}}" selected="selected">{{Session::get('pcode')}}</option>
                              @endif
                        </select>
                      </div>
                       <!-- <label for="vendorCodelable" class="col-sm-2 col-form-label ">PO Number</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control mytxt" style="height: 74%" id="po_no"  name="po_no"  placeholder="PO Number" value="@if($po=Session::get('po_no')) {{ $po}} @else {{ old('po_no') }}  @endif">
                      </div> -->
                    </div>
                         <div class="form-group row">
                                <label for="vendorCodelable" class="col-sm-3 col-form-label">Vendor Code</label>
                      <div class="col-sm-3">
                        <select class="form-control mytxt" id="v_code" name="v_code">
                       <option value="">-Select Vendor Code--</option>
                      @foreach($usrs as $usr)
                    <option value="{{$usr->VenderCode}}" 

                   @if(Session::get('v_code')==$usr->VenderCode) selected="selected" @endif @if(old('v_code') == $usr->VenderCode) selected="selected"   @endif> {{  $usr->VenderCode }}</option>
                    @endforeach
                        </select>
                      </div>

                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Po Number</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control mytxt" style="height: 74%" id="po_no"  name="po_no"  placeholder="PO Number" value="@if($po=Session::get('po_no')) {{ $po}} @else {{ old('po_no') }}  @endif">
                      </div>
                    
                    </div>
                   <input type="hidden" name="header_export_type" id="header_export_type">
                    <input type="hidden" name="item_export_type" id="item_export_type">
                    <button type="submit" class="btn btn-gradient-primary mr-2">Delivery Schedules</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
         
       
        

 @php
                $i=1
                @endphp

      @if($po_headers=Session::get('po_headers'))
          <div class="col-12 grid-margin">
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">PO Header</h4>

                 <!--  <p class="page-description">Add class <code>.table-striped</code> for table</p> -->
                  <div class="row">
                    
                    <div class="table-responsive">
                        
                       <button type="button" class="btn btn-gradient-info btn-rounded btn-fw" style="float: right;" onclick="export_header()">Export</button>
                     
                      <table class="table  table-bordered">
                        <thead>
                        
                         <tr>
                         <th style="width: 10px">#</th>
                  <th>Po Number</th>
                  <th>Company Code</th>
                  <th>PO category</th>
                  <th>Vendor Code</th>
                   <th>Vendor Name</th>
                  <th>PO Org.</th>
                  <th>PO Group</th>
                   <th>Currency</th>
                       </tr>
                        </thead>
                        <tbody>
                           @php
                          $cls="";
                          $i=1;
                          @endphp
                        @foreach($po_headers as $header)
                           @if($i%4==0)
                 @php $cls="table-info"; @endphp
                 @elseif($i%4==1)
                  @php  $cls="table-warning"; @endphp
                  @elseif($i%4==2)
                   @php  $cls="table-success"; @endphp
                   @elseif($i%4==3)
                   @php  $cls="table-primary"; @endphp
                   @endif
              <tr  class="{{ $cls }}">
                  <td>{{ $i++}}</td>
                  <td>{{ $header->PO_Number }}</td>
                  <td>{{ $header->Company_Code }}</td>
                  <td>{{ $header->PO_Category }}</td>
                  <td>{{ $header->Vendor_Code }}</td>
                  <td>{{ $header->Vendor_Name }}</td>
                  <td>{{ $header->PO_Org }}</td>
                  <td>{{ $header->PO_Group }}</td>
                  <td>{{ $header->Currency }}</td>
                </tr>
                @endforeach
                @if(count($po_headers)==0)
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

             @php
                $i=1
                @endphp

      @if($po_details=Session::get('po_details'))
          <div class="col-12 grid-margin">
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">PO Item Details</h4>

          
                  <div class="row">
                    
                    <div class="table-responsive">
                       
                       <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw" style="float: right;" onclick="export_header_items()">Export</button>
                     
                      <table class="table  table-bordered">
                        <thead>
                        
                         <tr>
                         <th style="width: 10px">#</th>
                  <th>Po Number</th>
                  <th>Po Item No.</th>
                  <th>Material</th>
                  <th>Material Description</th>
                   <th>Stock Quantity</th>
                  <th>UOM</th>
                 
                       </tr>
                        </thead>
                        <tbody>
                           @php
                        $cls="";
                 $i=1;
                   @endphp
                         @foreach($po_details as $po_dtl) 
                           @if($i%4==0)
                 @php $cls="table-info"; @endphp
                 @elseif($i%4==1)
                  @php  $cls="table-warning"; @endphp
                  @elseif($i%4==2)
                   @php  $cls="table-success"; @endphp
                   @elseif($i%4==3)
                   @php  $cls="table-primary"; @endphp
                   @endif
              <tr  class="{{ $cls }}">
                 <td>{{$i++}}</td>
                  <td>{{$po_dtl->po_number}}</td>
                  <td>{{$po_dtl->PO_Item_Code}}</td>
                  <td>{{$po_dtl->Material_Code}}</td>
                  <td>{{$po_dtl->Material_description}}</td>
                  <td>{{$po_dtl->PO_QTY}}</td>
                  <td>{{$po_dtl->UOM}}</td>
             
                  
                </tr>
                @endforeach
                @if(count($po_details)==0)
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
  function export_header()
  {
    $("#item_export_type").val("");
    $("#header_export_type").val("excel");
    $("#main_form").submit();
  }

function export_header_items()
{
  $("#header_export_type").val("");
    $("#item_export_type").val("excel");
    $("#main_form").submit();
}
</script>