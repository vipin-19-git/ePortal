@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
            Change Password
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Change Password</h4>
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
                  <form  action=" {{ route('admin.update_password')}}" method="post" id="admnchpass">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Current Password</label>
                      <div class="col-sm-3">
                     <input type="password" class="form-control pull-right" name="old_password" value="{{old('old_password')}}" id="old_password">
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">New Password</label>
                      <div class="col-sm-3">
                        <input type="password" class="form-control pull-right" name="new_password" value="{{old('new_password')}}"  id="new_password">
                      </div>
                       <!-- <label for="vendorCodelable" class="col-sm-2 col-form-label ">PO Number</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control mytxt" style="height: 74%" id="po_no"  name="po_no"  placeholder="PO Number" value="@if($po=Session::get('po_no')) {{ $po}} @else {{ old('po_no') }}  @endif">
                      </div> -->
                    </div>
                         <div class="form-group row">
                                <label for="vendorCodelable" class="col-sm-3 col-form-label">Confirm Password</label>
                      <div class="col-sm-3">
                       <input type="text" class="form-control pull-right" name="confirm_password"  value="{{old('confirm_password')}}"  id="confirm_password">
                      </div>

                    
                    
                    </div>
                   <input type="hidden" name="header_export_type" id="header_export_type">
                    <input type="hidden" name="item_export_type" id="item_export_type">
                    <button type="submit" class="btn btn-gradient-primary mr-2">Update</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
         
       
        

 

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