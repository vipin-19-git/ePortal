@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
             Add Vendor
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Vendor</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card" style="@if (! empty($users) && ! isset($user_edit))display: none @endif" id="main_form">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Add Vendor</h4>
              
                 <form action="@if(isset($user_edit))  {{ route('admin.update_vendor') }} @else {{ route('admin.create_Vendor') }} @endif" method="post" enctype="multipart/form-data" >
                    @csrf
             
                     <div class="form-group row" id="vendorCodeFrm">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Vendor Code</label>
                      <div class="col-sm-3">
                    <input type="text" class="form-control " id="v_code" name="v_code"  placeholder="Vendor Code" value="@if(isset($user_edit->VenderCode)){{$user_edit->VenderCode}}@else{{old('v_code')}}@endif" oninput="is_vendor_Available()">
                    <p id="msg_inv">  </p>
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Name</label>
                      <div class="col-sm-3">
                           <input type="text" class="form-control " style="height: 74%" id="v_name" name="v_name"  placeholder="Vendor Name" value="@if(isset($user_edit->UserName)){{$user_edit->UserName}}@else{{old('v_name')}}@endif" >
                      </div>
                  
                    </div>
                    <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Password</label>
                      <div class="col-sm-3">
                         <input type="password" id="V_password" name="V_password"  class="form-control"  value="@if(isset($user_edit->WebPassword)){{$user_edit->WebPassword}} @else{{old('V_password')}}@endif" placeholder="Password">
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Re-Password</label>
                       <div class="col-sm-3">
                         <input type="password" id="v_confirm_password" name="v_confirm_password"  class="form-control" value="{{ old('v_confirm_password') }}" placeholder="Re-Password">
                        </div>
                    </div>
                    <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Email </label>
                      <div class="col-sm-3">
                         <input type="email" id="v_email" name="v_email"  class="form-control"  value="@if(isset($user_edit->Email)) {{$user_edit->Email}} @else  {{ old('v_email') }} @endif"  placeholder="Email">
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">User Type</label>
                       <div class="col-sm-3">
                      <select class="form-control mytxt" id="user_type" name="user_type">
                  <option value="">--User Type--</option>
                     <option value="1" @if(isset($user_edit->IsHoAdmin) && $user_edit->IsHoAdmin=='1' ) selected="selected"  @else @if(old('user_type') =='1') selected="selected" @endif  @endif >Admin</option>
                     <option value="0"  @if(isset($user_edit->IsHoAdmin) && $user_edit->IsHoAdmin=='0' ) selected="selected"  @else @if( old('user_type') =='0') selected="selected"  @endif  @endif selected="selected">Vendor</option>
                        </select>
                      </div>

                    </div>

                    <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">User Status </label>
                      <div class="col-sm-3">
                         <select class="form-control" id="user_status" name="user_status"  class="form-control">
                     <option value="">--User Status--</option>
                     <option value="1" @if(isset($user_edit->IsActive) && $user_edit->IsActive==1) selected="selected"  @endif  
                      @if(old('user_status')==1) selected="selected" @endif>Active</option>
                     <option value="2" @if(isset($user_edit->IsActive) && $user_edit->IsActive==1) selected="selected" @endif
                       @if(old('user_status')==2) selected="selected" @endif>Inactive</option>
                  
                  </select>
                  </div>
                </div>
           <div class="form-group row">
                  <div class="col-sm-12" style="padding: 1px;background: linear-gradient(to left, #ff8e97, #8b34cc);">
                </div>
            </div>
            <div class="form-group row">
              <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Vendor Contact Person Name </label>
                <div class="col-sm-3">
                  <input type="text" id="vcp_name" name="vcp_name"  class="form-control"
          value="@if(isset($user_edit->Vend_Contact_P_Name)){{$user_edit->Vend_Contact_P_Name}}@else{{old('vcp_name')}}@endif"  placeholder="Name">
                </div>
               <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Vendor Contact Person Phone</label>
                <div class="col-sm-3">
                <input type="text" id="vcp_phone" name="vcp_phone"  class="form-control" value="@if(isset($user_edit->Vend_Contact_P_PhoneNo)){{$user_edit->Vend_Contact_P_PhoneNo}}@else{{old('vcp_phone')}}@endif" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Phone">
              </div>
              </div>
              <div class="form-group row">
              <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Vendor Contact Person Email </label>
                <div class="col-sm-3">
                  <input type="email" id="vcp_email" name="vcp_email"  class="form-control" value="@if(isset($user_edit->Vend_Contact_P_EmailID)) {{$user_edit->Vend_Contact_P_EmailID}}@else{{old('vcp_email')}} @endif" placeholder="Email">
                </div>
               <label for="vendorCodelable" class="col-sm-3 col-form-label">Vendor Capcity</label>
                <div class="col-sm-3">
                <input type="text" id="v_capcity" name="v_capcity"  class="form-control" value="@if(isset($user_edit->capcity)) {{$user_edit->capcity}}@else{{old('v_capcity')}}@endif" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Capcity">
              </div>
              </div>
               <div class="form-group row">
                  <div class="col-sm-12" style="padding: 1px;background: linear-gradient(to left, #ff8e97, #8b34cc);">
                </div>
              </div>

            <div class="form-group row">
              <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Company Contact Person Name </label>
                <div class="col-sm-3">
                   <input type="text" id="ccp_name" name="ccp_name"  class="form-control" value="@if(isset($user_edit->MSite_Contact_P_Name)){{$user_edit->MSite_Contact_P_Name}}@else{{old('ccp_name')}}@endif" placeholder=" Name">
                </div>
               <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Company Contact Person Phone</label>
                <div class="col-sm-3">
                <input type="text" id="ccp_phone" name="ccp_phone"  class="form-control" value="@if(isset($user_edit->MSite_Contact_P_PhoneNo)){{$user_edit->MSite_Contact_P_PhoneNo}}@else{{old('ccp_phone')}}@endif" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10"  placeholder="Phone" />
              </div>
              </div>
              <div class="form-group row">
              <label for="vendorCodelable" class="col-sm-3 col-form-label" style="white-space: nowrap;">Company  Contact Person Email </label>
                <div class="col-sm-3">
                   <input type="email" id="ccp_email" name="ccp_email"  class="form-control" value="@if(isset($user_edit->Msite_Contact_P_EmailID)) {{$user_edit->Msite_Contact_P_EmailID}}@else{{old('ccp_email')}}@endif" placeholder="Email">
                </div>
         
              </div>
                   <input type="hidden" name="edit_vend_id" value="@if(isset($user_edit->id)){{$user_edit->id}}@else 1 @endif">
                    <button type="submit" class="btn btn-gradient-primary mr-2"> @if(isset($user_edit)) Update @else Save @endif </button>
                    <button class="btn btn-light" type="reset" onclick="cancle_vendor()">Cancel</button>
                  </form>
                </div>
              </div>
            </div>

           <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Vendor List</h4>

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
         
                      <button type="button" class="btn btn-gradient-warning btn-rounded btn-icon" style="float: right;" name="add_vendor" id="add_vendor" value="add_new_vendor" onclick="show_hideForm()">
                          <i class="mdi mdi-plus"></i>
                        </button>
                               
            
                      <table class="table  table-bordered" id="printTable">
                        <thead>
                          
                     <tr>
                  <th>#</th>
                  <th>Vendor Code</th>
                  <th>Vendor Name</th>
                  
                  <th>User Type </th>
                  <th>Capcity</th> 
                  <th>Email</th>   
                   <th>Status</th>
                  <th colspan="2" style="text-align: center">Action</th>   
                 
               
        </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                 @forelse($users as $user)
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
                <td>{{$user->VenderCode}}</td>
                <td>{{$user->UserName}}</td>
              
                <td>@if($user->IsHoAdmin==1) Admin @else Vendor @endif</td>
                <td>{{$user->capcity}}</td>
                <td>{{$user->Email}}</td>
                  <td> <div class="badge @if($user->IsActive==1) badge-gradient-success @else badge-gradient-danger @endif" onclick="change_vendor_status({{$user->IsActive}},{{$user->VenderCode}})" id="vndr_stat">@if($user->IsActive==1)Active @else In-active @endif </div> </td>

                     <td >
                      <form action="{{ route('admin.edit_vendor') }}" method="GET">
                     <input type="hidden" name="editid" value="{{$user->id}}"> 
                      <button type="submit" class="btn btn-xs btn-gradient-primary">
                         <i class="mdi  mdi-pencil" data-toggle="tooltip" title="Edit Vendor Details!"></i>
                         Edit
                    </button>
                  </form>
                    </td>
                  <td >
                <form action="{{ route('admin.delete_Vendor') }}" method="post">
                     @csrf
                  <input type="hidden" name="delvendid" value="{{$user->id}}">
                      <button type="submit" class="btn btn-xs btn-gradient-danger">
                        <i class="mdi mdi-delete"  data-toggle="tooltip" title="Delete vendor Details!"></i> 
                       Delete
                    </button>
                 </form>
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
                           {!! $users->render() !!}
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
   $("#add_vendor").hide();

  }
  
function cancle_vendor()
{
/*$("#main_form").slideToggle(2000);
 $("#add_vendor").show();  */
 location.href="{{ route('admin.add_vendor')}}";
}
function change_vendor_status(vnd_status,v_code)
{
  if(vnd_status==1)
  {
    vnd_status=0;
  }
  else
  {
    vnd_status=1;
  }
       $.ajax({

           type:'POST',

           url:'{{ route("admin.change_vnd_status") }}',

           data:{

            vnd_status:vnd_status,
            v_code:v_code,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data){
           
             if(data.success)
             {
               if(vnd_status==1)
               {
                  //$("#vndr_stat").removeClass('badge badge-gradient-success');
                  // $("#vndr_stat").addClass('badge badge-gradient-danger');
                   $("#vndr_stat").removeAttr('onclick');
                   $("#vndr_stat").attr('onclick', 'change_vendor_status(1,'+v_code+')');
                  
               }
               else
               {
                  //$("#vndr_stat").removeClass('badge badge-gradient-danger');
                   //$("#vndr_stat").addClass('badge badge-gradient-success');
                   $("#vndr_stat").removeAttr('onclick');
                   $("#vndr_stat").attr('onclick', 'change_vendor_status(0,'+v_code+')');
               }
               $("#vendorCodeFrm").addClass("form-control-danger");
              $("#msg_inv").html('<label id="vendor-code-error" class="error mt-2 text-danger" for="vcode">Vendor Code already exist !</label>')
             }
             else
             {
             
              $("#vendorCodeFrm").removeClass("form-control-danger");
             
              $("#msg_inv").html('');
              
               
              
             }
           }

        });

}
  function is_vendor_Available()
  {
     var v_code = $("input[name=v_code]").val();
       $.ajax({

           type:'POST',

           url:'{{ route("admin.is_available_vndr") }}',

           data:{

            v_code:v_code,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data){
           
             if(data.success)
             {
               
               $("#vendorCodeFrm").addClass("form-control-danger");
              $("#msg_inv").html('<label id="vendor-code-error" class="error mt-2 text-danger" for="vcode">Vendor Code already exist !</label>')
             }
             else
             {
             
              $("#vendorCodeFrm").removeClass("form-control-danger");
             
              $("#msg_inv").html('');
              
               
              
             }
           }

        });

  


  }
</script>