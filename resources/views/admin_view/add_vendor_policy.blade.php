@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
             Add New Vendor Policy
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Vendor Policy</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card" style="@if (! empty($notifications) )display: none @endif" id="main_form">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Add New Vendor Policy</h4>
              
                 <form action="{{ route('admin.add_vpolicy') }}" method="post" enctype="multipart/form-data" >
                    @csrf
             
                 
                  <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Entry date</label>
                      <div class="col-sm-5">
                    <input type="text" class="form-control" name="entry_date" id="entry_date" value="{{date('Y-m-d')}}" readonly="">
                    <p id="msg_inv">  </p>
                      </div>
                    </div>
                        <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Upload Policy</label>
                      <div class="col-sm-5" >
                         <input type="file" class="form-control"  name="vpolicy" id="vpolicy" required="">
                        
                      <p id="msg_inv">  </p>
                      </div>
                    </div>
                    <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Valied Up-to</label>
                      <div id="datepicker-popup" class="col-sm-5 input-group date datepicker">
                        <input type="text" class="form-control"  id="datepicker" name="valied_date"  value="{{ old('valied_date') }}@if($stdt=Session::get('stdt')) {{ date('m/d/Y',strtotime($stdt)) }} @endif">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    <p id="msg_inv">  </p>
                     </div>
            
                   <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Status</label>
                      <div  class="col-sm-5">
                        <select class="form-control" id="policy_status" name="policy_status"  class="form-control" >
                         <option value="">-Select Status--</option>
                       <option value="1" @if(old('policy_status')==1) selected="" @endif >Active</option>
                       <option value="0" @if(old('status')==0) selected="" @endif >Inactive</option>
                       </select>
                      </div>
                    <p id="msg_inv">  </p>
                     </div>
       
                   <input type="hidden" name="edit_vend_id" value="@if(isset($user_edit->id)){{$user_edit->id}}@else 1 @endif">
                    <button type="submit" class="btn btn-gradient-primary mr-2"> Save  </button>
                    <button class="btn btn-light" type="reset" onclick="cancle_vendor()">Cancel</button>
                  </form>
                </div>
              </div>
            </div>

           <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Policy Details</h4>

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
                  <div class="row">
                    
                    <div class="table-responsive">
         
                      <button type="button" class="btn btn-gradient-warning btn-rounded btn-icon" style="float: right;" name="add_vendor_policy" id="add_vendor_policy" value="add_new_policy" onclick="show_hideForm()">
                          <i class="mdi mdi-plus"></i>
                        </button>
                               
            
                      <table class="table  table-bordered" id="printTable">
                        <thead>
                          
                     <tr>
          <th>#</th>
          <th>Policy</th>
           <th>Entry date</th>
           <th>Valied Upto</th>
          <th>Status</th>
         </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                 @forelse($policies as $policy)
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
                  <td>{{$i++}}</td>
          <td>
            <form action="{{ route('admin.getVendorPolicy') }}" method="post">@csrf
            <button type="submit" class="btn btn-md btn-primary" name="get_policy" value="{{$policy->policy}}" ><i class="fa fa-download" aria-hidden="true"></i>Download 
            </button></form>
          </td>
           <td>{{date('d-M-Y',strtotime($policy->entry_date)) }}</td>
          <td> {{date('d-M-Y',strtotime($policy->valied_upTo)) }}</td>
         
          <td>
       
         @if($policy->status==1)
          <div class="badge badge-gradient-success" onclick="chStatus({{$policy->id}})">
         Active
        </div>
          <form method="post" action="{{ route('admin.updateVendorPolicy') }}" id="{{$policy->id}}">
            @csrf
          <input type="hidden" name="pid" value="{{$policy->id}}">
           <input type="hidden" name="status" value="0">
          </form>
          @else
          <div class="badge badge-gradient-danger" onclick="chStatus({{$policy->id}})">
         
           Inactive
         
         </div>
           <form method="post" action="{{ route('admin.updateVendorPolicy') }}" id="{{$policy->id}}">
            @csrf
          <input type="hidden" name="pid" value="{{$policy->id}}">
           <input type="hidden" name="status" value="1">
          </form>
         @endif
           </td>
        </tr>
         @empty
         <t>
    
            <td style="text-align: center;color: red" colspan="6">No any Query</td>
                 
                </tr>
                @endforelse
                          
                        </tbody>
                      </table>
                     <div style="float: right">
                           {!! $policies->render() !!}
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



@endsection
<script type="text/javascript">



  function show_hideForm()
  {
    
    $("#main_form").slideToggle(2000);
   $("#add_vendor_policy").hide();

  }
  
function cancle_vendor()
{

 location.href="{{ route('admin.add_vendorPolicy_frm')}}";
}
function chStatus(id)
  {
    $("#"+id).submit();
  }
</script>