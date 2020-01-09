@extends('layouts.admin_master')

@section('content')
 <style type="text/css">
   .modal-body {
    position: relative;
    overflow-y: auto;
    max-height: 400px;
    padding: 15px;
}
 </style>
           <div class="page-header">
            <h3 class="page-title">
            New Vendor Reg. Request
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">New Vendor Reg. Request</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card" id="add_new_notification" style="@if (! $errors->any()) display: none @endif">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">New Vendor Reg. Request</h4>
              
                 <form  method="post"  action="{{ route('admin.change_vendor_Status') }}" id="vendor_req_frm">
                    @csrf
             
                 
                  <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Vendor Name</label>
                      <div class="col-sm-5">
                    <input type="text" class="form-control" name="vendrname" id="vendrname" readonly="">
                    <p id="msg_inv">  </p>
                      </div>
                    </div>
                        <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Status</label>
                      <div class="col-sm-5" >
                        <select class="form-control" id="status" name="status"  class="form-control" >

          <option value="">-Select Status--</option>
          <option value="Approved" @if(old('status')=="Approved") selected="" @endif >Approved</option>
          <option value="Pending" @if(old('topic')=="Pending") selected="" @endif >Pending</option>
          <option value="Hold" @if(old('status')=="Hold") selected="" @endif >Hold</option>
           <option value="Cancel" @if(old('status')=="Cancel") selected="" @endif >Cancel</option>
        </select>
                        
                      <p id="msg_inv">  </p>
                      </div>
                    </div>
                    <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Date</label>
                      <div class="col-sm-5">
                        <input type="text" class="form-control" name="date" id="date" value="{{date('Y-m-d')}}" readonly="">
                      </div>
                    <p id="msg_inv">  </p>
                     </div>
            
                   <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Remarks</label>
                      <div  class="col-sm-5">
                         <textarea class="form-control" rows="5"  name="remarks"  id="remarks" required style="resize: none;"></textarea>
                      </div>
                    <p id="msg_inv">  </p>
                     </div>
       
                   <input type="hidden" class="form-control"  name="vendor_id" id="vendor_id" >
                    <button type="submit" class="btn btn-gradient-primary mr-2" name="change_st" id="change_st" value="change_vend_St"> Save  </button>
                    <button class="btn btn-light" type="reset" onclick="cancle_vendor()">Cancel</button>
                  </form>
                </div>
              </div>
            </div>

           <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">New Vendor Reg. Info</h4>

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
          <table class="table  table-bordered" id="printTable">
          <thead>
              <tr>
          <th>#</th>
          <th>Vendor Name</th>
          <th>Business Type</th>
           <th>Mobile</th>
          <th>Email</th>
          <th>Phone No.</th>
          <th>GST No.</th>
          <th>Pan No.</th>
          <th>Status</th>
          <th colspan="3" style="text-align: center;">Action</th>
           </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                 @forelse($vendors as $vendor )
      
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
          <td>{{$vendor->vendor_name}}</td>
          <td>@if($vendor && $vendor->BusinessType) {{ $vendor->BusinessType->name }} @endif</td>
           <td>{{$vendor->mobile}}</td>
           <td>{{ $vendor->email}}</td>
           <td>{{ $vendor->phone_no}}</td>
           <td>{{ $vendor->gst_no}}</td>
          <td>{{ $vendor->pan_no}}</td>
          <td >
        @if($vendor->vendor_status_by_admin== "Pending")
          <label class="badge badge-gradient-warning">
           
          @endif
         @if($vendor->vendor_status_by_admin== "Cancel")
          <label class="badge badge-gradient-danger">
         
          @endif
         @if($vendor->vendor_status_by_admin== "Hold")
        <label class="badge badge-gradient-info">
          
          @endif
        @if($vendor->vendor_status_by_admin=="Approved")
          <label class="badge badge-gradient-success">
           
           @endif
        {{$vendor->vendor_status_by_admin}}   </label></td>
        
         <td colspan="1"> 
          
          <label class="badge badge-gradient-primary" onclick="data_on_modal({{$vendor->id}},'{{$vendor->vendor_name}}')" style=" white-space: nowrap;">
            View
          </label>
          </td>
          <td colspan="2">
           @if($vendor->vendor_status_by_admin=="Hold" || $vendor->vendor_status_by_admin=="Pending")
               <label class="badge badge-gradient-info" onclick="show_hideForm({{$vendor->id}},'{{$vendor->vendor_name}}')" style=" white-space: nowrap;">
            Change Status
          </label>
        
          @endif
           
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
                           {!! $vendors->render() !!}
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

      
                  <!-- Dummy Modal Starts -->
                  <div class="modal" id="myModal" style="padding-right: 130px;padding-left: 309px;">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id=
                          "modal_title">Modal title</h5>
                          <button type="button" class="close"  data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                              <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="Basic-tab" data-toggle="tab" href="#vendor_info" role="tab" aria-controls="vendor_info" aria-selected="true">Vendor Info</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact_info" role="tab" aria-controls="contact_info aria-selected="false">Contact Info</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="refrence-tab" data-toggle="tab" href="#refrence" role="tab" aria-controls="refrence" aria-selected="false">Referance</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="certification-tab" data-toggle="tab" href="#certification" role="tab" aria-controls="certification" aria-selected="false">Certification</a>
                    </li>
                       <li class="nav-item">
                      <a class="nav-link" id="capcity-tab" data-toggle="tab" href="#capacity" role="tab" aria-controls="capacity" aria-selected="false">Capacity Detaile</a>
                    </li>
                  </ul>
                  <div class="tab-content">

                    <div class="tab-pane fade show active" id="vendor_info" role="tabpanel" aria-labelledby="Basic-tab">
                      <form action="#" method="post" id="basicForm">
                       <div class="form-group row">
                         <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Supplier Name</label>
                        <div class="col-sm-8">
                       <input type="text" class="form-control input-sm" name="vendor_name"  id="vendor_name" readonly="">
                       </div>
                      </div>
                      <div class="form-group row">
                         <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Business Type</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="btype"  id="btype" readonly="">
                       </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Country</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="country"  id="country" readonly="">
                       </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">State</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="state"  id="state" readonly="">
                       </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">City</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="city"  id="city" readonly="">
                       </div>
                      </div>

                         <div class="form-group row">
                         <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Address 1</label>
                        <div class="col-sm-8">
                       <input type="text" class="form-control" name="address_1"  id="address_1" readonly="">
                       </div>
                      </div>
                       
                      <div class="form-group row">
                         <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Address 2</label>
                        <div class="col-sm-8">
                       <input type="text" class="form-control" name="address_2"  id="address_2" readonly="">
                       </div>
                      </div>
                      
                      <div class="form-group row">
                         <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Address 3</label>
                        <div class="col-sm-8">
                       <input type="text" class="form-control" name="address_3"  id="address_3" readonly="">
                       </div>
                      </div>
                              <div class="form-group row">
                        <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">GST</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="gst_no"  id="gst_no" readonly="">
                       </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">PAN</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="pan_no"  id="pan_no" readonly="">
                       </div>
                      </div>
                            <div class="form-group row">
                        <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">CIN</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="cin_no"  id="cin_no" readonly="">
                       </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Mobile</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="mob"  id="mob" readonly="">
                       </div>
                      </div>
                            <div class="form-group row">
                        <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label" style=" white-space: nowrap;">Proprietor Name</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="proprietor"  id="proprietor" readonly="">
                       </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="email"  id="email" readonly="">
                       </div>
                      </div>
                            <div class="form-group row">
                        <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="phone"  id="phone" readonly="">
                       </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label">Fax No</label>
                        <div class="col-sm-3">
                       <input type="text" class="form-control" name="fax"  id="fax" readonly="">
                       </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-sm-1">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label"  style=" white-space: nowrap;">Nature of Business </label>
                        <div class="col-sm-8">
                        <textarea class="form-control" rows="5"  name="nature_of_business"  id="nature_of_business" required style="resize: none;"></textarea>
                       </div>
                    </div>
                    </form>
                      </div>

               <div class="tab-pane fade" id="contact_info" role="tabpanel" aria-labelledby="contact-tab">
                 <form action="#" method="post" onsubmit="" id="contForm">
                <h4 class="card-title">Contact Information</h4>
                <table class="table  table-striped" id="printTable">
                  <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                  <th>Contact Person Name</th>
                  <th>Phone</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Designation</th>
                  <th>Department</th>
                  </tr>
                </thead>
                <tbody id="contact_body">
                <tr id="contact1">
               <td>1</td>
          <td><input type="text" class="form-control " name="cname[]" id="cname1" readonly=""></td>
          <td><input type="text" class="form-control " name="cfon[]" id="cfon1" readonly=""> </td>
          <td><input type="text" class="form-control " name="cmob[]" id="cmob1" readonly=""></td>
          <td><input type="email" class="form-control " name="cemail[]" id="cemail1" readonly=""></td>
          <td><input type="text" class="form-control " name="cdesig[]" id="cdesig1" readonly=""></td>
          <td><input type="text" class="form-control " name="cdepart[]" id="cdepart1" readonly=""></td>
         </tr>
         </tbody>
        </table>  
        </form> 
      </div>

      <div class="tab-pane fade" id="refrence" role="tabpanel" aria-labelledby="refrence-tab">
        <form action="#" method="post" onsubmit="" id="refForm">
        <h4 class="card-title">Referance</h4>
         <table class="table  table-striped" id="printTable">
          <thead>
          <tr>
           <th style="width: 10px">#</th>
            <th>Referance Name</th>
            <th>Address</th>
             <th>Contact No.</th>
            <th>Remarks</th>
            </tr>
         </thead>
         <tbody id="reference_body">
             <tr id="ref1">
              <td>1</td>
             <td><input type="text" class="form-control" name="refname[]" id="refname1" readonly=""></td>
             <td><input type="text" class="form-control" name="refaddr[]" id="refaddr1" readonly=""></td>
             <td><input type="text" class="form-control" name="refcont[]" id="refcont1" readonly=""></td>
             <td><input type="text" class="form-control" name="refrem[]" id="refrem1" readonly=""></td>
           </tr>
        </tbody>
        </table> 
        </form>  
     </div>

      <div class="tab-pane fade" id="certification" role="tabpanel" aria-labelledby="certification-tab">
        <form action="#" method="post" id="certForm">
        <h4 class="card-title">Certification Details</h4>
         <table class="table  table-striped" id="printTable">
          <thead>
          <tr>
            <th style="width: 10px">#</th>
             <th>Certificate  Name</th>
              <th>Valid Upto</th>
              <th>Remarks</th>
              <th>Download Cerificate</th>
            </tr>
             </thead>
           <tbody id="certificate_body">
              <tr id="cert1">
              <td>1</td>
             <td><input type="text" class="form-control " name="certname[]" id="certname1" readonly=""></td>
             <td><input type="text" class="form-control " name="certtupto[]" id="certtupto1" readonly=""></td>
             <td><input type="text" class="form-control " name="certrmrk[]" id="certrmrk1" readonly=""></td>
             <td><input type="file" class="form-control " name="certdoc[]" id="certdoc1" readonly=""></td>
            </tr>
          </tbody>
        </table>   
      </form>
      </div>

                      <div class="tab-pane fade" id="capacity" role="tabpanel" aria-labelledby="capcity-tab">
                           <form action="#" method="post" id="capform">
                      <div class="form-group row">
                        <div class="col-sm-2">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label" style=" white-space: nowrap;">Capacity Details </label>
                        <div class="col-sm-6">
                        <textarea class="form-control" rows="5"  name="cap_dtl" id="cap_dtl" readonly="" style="resize: none;"></textarea>
                       </div>
                    </div>
                       <div class="form-group row">
                        <div class="col-sm-2">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label" style=" white-space: nowrap;">No. of workers </label>
                        <div class="col-sm-6">
                         <input type="text" class="form-control " name="no_workr" id="no_workr" readonly="">
                       </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label" style=" white-space: nowrap;">No. of staff </label>
                        <div class="col-sm-6">
                        <input type="text" class="form-control " name="no_staff" id="no_staff" readonly="">
                       </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2">
                          </div>
                       <label for="vendorCodelable" class="col-sm-2 col-form-label" style=" white-space: nowrap;">No. of Machine </label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control " name="no_machine" id="no_machine" readonly="">
                       </div>
                    </div>
                  </form>
                    </div>

                  </div>
                </div>
                        <div class="modal-footer">
                         
                          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
                



@endsection

<script type="text/javascript">
  function cancle_vendor()
{

 location.href="{{ route('admin.show_New_Vendor_Request')}}";
}
  function show_hideForm(id,user)
  {
    
   document.getElementById("vendor_req_frm").reset();
   $("#vendrname").val(user);
    $("#vendor_id").val(id);
    $("#add_new_notification").slideToggle(2000);
     $('.chstatus').off("click");
    //  $('.chstatus').unbind('click');
  }
  function data_on_modal(id,vendorname)
  {
        
       
    $("#modal_title").html(vendorname);
    $("#myModal").modal('show');
    $("#myModal").find("input,textarea,select").val('');
       document.getElementById("basicForm").reset();
       document.getElementById("contForm").reset();
       document.getElementById("refForm").reset();
       document.getElementById("certForm").reset();
       document.getElementById("capform").reset();
      
       
      
     $.ajax({
            type:'POST',
            url:'{{ route("admin.get_vendor_reg_data") }}',
            data:{ vend_id:id, "_token": "{{ csrf_token() }}" },
            success:function(data)
            {
              
              $("#vendor_name").val(data.basic_info.vendor_name);
               $("#btype").val(data.basic_info.business_type.name);
               $("#country").val(data.basic_info.country.country_name);
               $("#state").val(data.basic_info.state.state_name);
               $("#city").val(data.basic_info.city.city_name);

               $("#address_1").val(data.basic_info.address_1);
               $("#address_2").val(data.basic_info.address_2);
               $("#address_3").val(data.basic_info.address_3);
               $("#gst_no").val(data.basic_info.gst_no);
               $("#pan_no").val(data.basic_info.pan_no); 
               $("#cin_no").val(data.basic_info.cin_no);
               $("#mob").val(data.basic_info.mobile);
               $("#proprietor").val(data.basic_info.proprietor);
               $("#email").val(data.basic_info.email);
               $("#phone").val(data.basic_info.phone_no); 
               $("#fax").val(data.basic_info.fax);
               $("#nature_of_business").val(data.basic_info.nature_of_business); 
                $("#contact_body").html('');
               for (var i = 0; i < data.contacts.length ; i++) 
               {
                 
               $("#contact_body").append('<tr><td>'+(i+1)+'</td>'+
          '<td><input type="text" class="form-control"  readonly="" value="'+ data.contacts[i].name+'"></td>'+
          '<td><input type="text" class="form-control" readonly="" value="'+ data.contacts[i].phone+'"> </td>'+
          '<td><input type="text" class="form-control"  readonly="" value="'+ data.contacts[i].mobile+'"></td>'+
          '<td><input type="email" class="form-control" readonly="" value="'+data.contacts[i].email+'"></td>'+
          '<td><input type="text" class="form-control" readonly="" value="'+data.contacts[i].designation+'"></td>'+
          '<td><input type="text" class="form-control"  readonly="" value="'+ data.contacts[i].department+'"></td></tr>');
       
               }

               $("#reference_body").html('');
                for (var i = 0; i < data.reference.length ; i++) 
               {

         $("#reference_body").append('<tr><td>'+(i+1)+'</td>'+
          '<td><input type="text" class="form-control"  readonly="" value="'+ data.reference[i].name+'"></td>'+
         '<td><input type="text" class="form-control" readonly="" value="'+ data.reference[i].address+'"></td>'+
        '<td><input type="text" class="form-control"  readonly="" value="'+ data.reference[i].contact+'"></td>'+
         '<td><input type="text" class="form-control" readonly="" value="'+ data.reference[i].remarks+'"></td>'+
        '</tr>');
        
                 
               }
                $("#certificate_body").html('');
                for (var i = 0; i < data.certification.length ; i++) 
               {
                 
             $("#certificate_body").append('<tr> <td>'+(i+1)+'</td>'+
             '<td><input type="text" class="form-control " readonly="" value="'+ data.certification[i].name+'"></td>'+
        '<td><input type="text" class="form-control " readonly="" value="'+ data.certification[i].valid_up_to+'"></td>'+
             '<td><input type="text" class="form-control " readonly="" value="'+ data.certification[i].remarks+'"></td>'+
             '<td><form action="'+'{{ route('admin.getCertificate') }}'+'" method="post">@csrf'+'<button type="submit" class="btn btn-block btn-warning" name="get_certifcate" value="'+data.certification[i].uploads+'" ><i class="fa fa-download" aria-hidden="true"></i>&nbsp&nbsp Download </button>'+
             '</form></td>'+'</tr>');
         
               }

               $("#cap_dtl").val(data.capcityDtl.capcity_dtl);
               $("#no_workr").val(data.capcityDtl.no_worker); 
               $("#no_staff").val(data.capcityDtl.no_staff);
               $("#no_machine").val(data.capcityDtl.no_machine); 
            } 

         });

  }

</script>