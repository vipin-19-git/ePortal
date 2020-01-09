@extends('layouts.suplier_master')

@section('content')
 <style type="text/css">
   .mytxt
   {
        width: 133%;
    margin-left: -40px;
    height: 62%;
    margin-top: 6px;
   }

 </style>
        <div class="page-header">
            <h3 class="page-title">
           Delivery Schedule  
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delivery Schedule</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
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
                  <h4 class="card-title">Delivery Schedule</h4>
               
                  <form  action="{{ route('vendor.get_delivery_schedule') }}" method="post">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Company Code</label>
                      <div class="col-sm-2">
                        <select class="form-control mytxt" id="c_code" name="c_code"  onchange="get_plant(this.value)">
                         <option value="">-Select Company Code--</option>
                      @foreach($companies as $company)
                    <option value="{{$company->CompanyCode}}" @if(old('c_code') == $company->CompanyCode) selected="selected" @endif @if(isset($ccode) && $ccode==$company->CompanyCode) selected="selected" @endif> {{  $company->CompanyName.'('.$company->CompanyCode.')' }}</option>
                    @endforeach
                        </select>
                      </div>
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Plant Code</label>
                      <div class="col-sm-2">
                         <select class="form-control mytxt" id="p_code" name="p_code">
                          <option value="">--Select Plant Code--</option>
                             @if(isset($pcode))
                             <option value="{{$pcode}}" selected="selected">{{$pcode}}</option>
                              @endif
                        </select>
                      </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label ">PO Number</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control mytxt" style="height: 74%" id="po_no"  name="po_no"  placeholder="PO Number" value="{{old('po_no')}}@if(isset($pono)){{$pono}}@endif">
                      </div>
                    </div>
                        <!--  <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Po Number</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="po_no"  name="po_no"  placeholder="PO Number" value="{{ old('po_no') }} @if(isset($pono)) {{ $pono }} @endif">
                      </div>
                    
                    </div> -->
                
                    <button type="submit" class="btn btn-gradient-primary mr-2">Delivery Schedules</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
 @php
                $i=1
                @endphp

      @if(!empty($po_headers))
          <div class="col-12 grid-margin">
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">PO Header</h4>

                 <!--  <p class="page-description">Add class <code>.table-striped</code> for table</p> -->
                  <div class="row">
                    
                    <div class="table-responsive">
                           <form action="{{route('vendor.export_header')}}" method="post">
                       @csrf

                       <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw" style="float: right;">Export</button>

                     </form>
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
                          $sch_pos="";
                          $i=1;
                          @endphp
                        @foreach($po_headers as $header)
                        @if($i==1)
                        @php $sch_pos=$header->PO_Number;@endphp
                        @else
                             @php $sch_pos=$sch_pos.",".$header->PO_Number;@endphp
                        @endif
                           @if($i%4==0)
                 @php $cls="table-info";@endphp
                 @elseif($i%4==1)
                  @php  $cls="table-warning"; @endphp
                  @elseif($i%4==2)
                   @php  $cls="table-success"; @endphp
                   @elseif($i%4==3)
                   @php  $cls="table-primary"; @endphp
                   @endif
              <tr  class="{{ $cls }}">
                  <td> {{$i++}}</td>
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

      @if(!empty($po_details))
          <div class="col-12 grid-margin">
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">PO Item Details</h4>

          
                  <div class="row">
                    
                    <div class="table-responsive">
                       <form action="{{route('vendor.export_detail')}}" method="post">
                        @csrf
                       <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw" style="float: right;">Export</button>
                       </form>
                       <form action="{{route('vendor.multiPoqrcode')}}" method="post">
                        @csrf
                        <input type="hidden" name="scheduled_po" id="scheduled_po" value="{{$sch_pos}}" >
                        <button type="submit" class="btn btn-gradient-primary btn-rounded btn-fw" style="float: right;">Generate Barcode</button>
                     </form>
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
                  <th>Action</th>
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
                  <td>
                     <form action="{{ route('vendor.qrcode') }}" method="post">
                          @csrf
                    <button type="submit" class="btn btn-gradient-primary mr-2" value="{{$po_dtl->po_number}}" name="gen_qr">Generate Barcode</button>
                   </form>
                 </td>
                  
                </tr>
                @endforeach
                @if(count($po_details)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="9">No any data</td>
                 
                </tr>
               @endif
                          
                        </tbody>
                      </table>
                      <div style="float: right">

           
            {{ $po_details->appends(request()->input())->links() }}
          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif

@endsection