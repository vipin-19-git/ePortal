@extends('layouts.admin_master')
@section('content')
  <div class="page-header">
            <h3 class="page-title">
             Invoice List
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoice List</li>
              </ol>
            </nav>
          </div>

          <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Invoice List</h4>

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
               <form action="{{ route('admin.delete_invoice') }}" method="post" enctype="multipart/form-data">
                  @csrf
               <div class="form-group row">
                <div class="col-sm-1">
                </div>
              <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Invoice Number </label>
                <div class="col-sm-3">
                  <input type="text" id="find_invoice" name="find_invoice"  class="form-control" 
                value="@if(isset($invoice)){{$invoice}}@endif">
                </div>
              
                <div class="col-sm-3">
                 <button type="submit" class="btn btn-gradient-info mr-2">Search </button>
              </div>
              </div>
            </form>
                  <div class="row">
                    
                    <div class="table-responsive">
         
                
                               
            
                      <table class="table  table-bordered" id="printTable">
                        <thead>
                          
                     <tr>
                  <th>#</th>
                 <th>Vendor Code</th>
                  <th>Invoice Number</th>
                   <th>Invoice Date</th>
                  <th>Action </th>    
                 
                 
               
        </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                  @forelse($bar_code_details as $details)
                 @php  $j++  @endphp
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
                <td>{{$details->VENDOR_Code}}</td>
                <td>{{$details->Invoice_No}}</td>
                <td>{{ date("d-M-Y",strtotime($details->Invoice_Date))}}</td>
                

                 
                  <td>
                  <form id="del_inv_{{$i}}" action="{{ route('admin.delete_invoice') }}" method="POST" style="display: none;">
                                        @csrf
                                        <input type="hidden" name="submit_v" value="del_inv">
                    <input type="hiden" name="invoice_no" value="{{$details->Invoice_No}}">
                                    </form>
               
                
                      <button type="submit" class="btn btn-xs btn-gradient-danger" onclick="event.preventDefault(); document.getElementById('del_inv_{{$i}}').submit();">
                        <i class="mdi mdi-delete"  data-toggle="tooltip" title="Delete invoice details!"></i> 
                       Delete
                    </button>
                
               </td>
        </tr>
         @empty
         <tr>
    
            <td style="text-align: center;color: red" colspan="6">No any Query</td>
                 
                </tr>
                @endforelse
                          
                        </tbody>
                      </table>
                     <div style="float: right">
                             {!! $bar_code_details->render() !!}
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endsection