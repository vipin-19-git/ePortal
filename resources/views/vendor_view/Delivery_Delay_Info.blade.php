@extends('layouts.suplier_master')

@section('content')
 
        <div class="page-header">
            <h3 class="page-title">
         Deliver Delay Information
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Deliver Delay Info</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Deliver Delay Information</h4>
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
                  <form  action="{{route('vendor.post_Delivery_Delay_Frm')}}" method="post">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Company Code</label>
                      <div class="col-sm-3">
                        <select class="form-control" id="c_code" name="c_code"   onchange="get_plant(this.value)">
                         <option value="">-Select Company Code--</option>
                      @foreach($companies as $company)
                    <option value="{{$company->CompanyCode}}" @if(old('c_code') == $company->CompanyCode) selected="selected" @endif @if(Session::get('ccode')==$company->CompanyCode) selected="selected" @endif> {{  $company->CompanyName.'('.$company->CompanyCode.')' }}</option>
                    @endforeach
                        </select>
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Plant Code</label>
                      <div class="col-sm-3">
                         <select class="form-control" id="p_code" name="p_code" >
                          <option value="">--Select Plant Code--</option>
                             @if(Session::get('pcode'))
                             <option value="{{Session::get('pcode')}}" selected="selected">{{Session::get('pcode')}}</option>
                              @endif
                        </select>
                      </div>
                    </div>
            <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Breakdown Type</label>
             <div class="col-sm-3">
             <select class="form-control" id="b_type" name="b_type" onchange="get_all_po()" required="">
              <option value="">--Select Type--</option>
            <option value="tool" @if(old('b_type') == "tool") selected="selected" @endif>tool</option>
            <option value="machine" @if(old('b_type') == "machine") selected="selected" @endif>machine</option>
            <option value="jigfeature"  @if(old('b_type') == "jigfeature") selected="selected" @endif>jigfeature</option>
            <option value="other" @if(old('b_type') == "other") selected="selected" @endif>other</option>
          </select>
          </div>
            <label for="vendorCodelable" class="col-sm-3 col-form-label">Supllier Name</label>
           <div class="col-sm-3">
          <input type="text" class="form-control" name="suplier_name"  id="suplier_name" value="{{old('suplier_name')}}" oninput="get_all_po()"
                   required="">
              </div>
           </div>
                         <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">From Date</label>
                            <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control" name="start_date"  id="datepicker" value="{{old('start_date')}}" oninput="get_all_po()"  required="">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                 
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">To Date</label>
                    
                          <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control" name="end_date"  id="datepicker2" value="{{old('end_date')}}" oninput="get_all_po()"  required="">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    
                    </div>
                
                 <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Purcahase Order no.</label>
             <div class="col-sm-3">
             <select class="form-control" id="pur_order_no" name="pur_order_no"  class="form-control" onchange="get_MatDescrp()"  required="">
              <option value="">--Select Po Number--</option>
          </select>
          </div>
            <label for="vendorCodelable" class="col-sm-3 col-form-label">Supllier Contact Person</label>
           <div class="col-sm-3">
          <input type="text" class="form-control" name="suplier_cont_name"  id="suplier_cont_name" value="{{old('suplier_cont_name')}}" oninput="get_all_po()"  required="">
              </div>
           </div>

            <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Material Code & Description </label>
             <div class="col-sm-3">
             <select class="form-control" id="mat_code_descrp" name="mat_code_descrp"  class="form-control"  required="">
              <option value="">--Select Material Code --</option>
          </select>
          </div>
            <label for="vendorCodelable" class="col-sm-3 col-form-label">Aval. qty at supplier end.</label>
           <div class="col-sm-3">
          <input type="text" class="form-control" id="invoice_barcode"  name="qty_at_sup"  id="qty_at_sup"  value="{{old('qty_at_sup')}}" oninput="get_all_po()"  required="">
              </div>
           </div>

           <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Reason</label>
             <div class="col-sm-9">
             <textarea class="form-control" rows="4" style="resize: none;" name="reason" placeholder="Enter Reason Here..." value="{{old('reason')}}" oninput="get_all_po()"  required=""></textarea>
          </div>
        
           </div>


                    <button type="submit" class="btn btn-gradient-primary mr-2">Delay Schedules</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
            @endsection

        <script type="text/javascript">

function get_all_po()
{
var c_code=$("#c_code").val();
var p_code=$("#p_code").val();

if(c_code=="")
{
alert("Select Compnay Code First !");
$("#c_code").focus();
}
else if(p_code=="")
{
alert("Select plant Code !");
$("#p_code").focus();
}
else
{

if($("#pur_order_no option").length<=1)
  {


       $.ajax({

           type:'POST',

           url:'{{ route("vendor.get_all_po") }}',

           data:{

            c_code:c_code,p_code:p_code,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data)
           {
              var default_plant='<option value="">--Select Po Number--</option>';
              var plant_opt="";
         
             if(data.count>0)
             {
              for(var i=0;i<data.count;i++)
              {
                plant_opt+='<option value="'+data.po_numbers[i].po_no+'">'+data.po_numbers[i].po_no+'</option>';
                 
                
              }
             }
             $("#pur_order_no").html(default_plant+plant_opt);
           }

        });
     }
   }
}
function get_MatDescrp()
{
var c_code=$("#c_code").val();
var p_code=$("#p_code").val();
var po_no=$("#pur_order_no").val();
if(c_code=="")
{
alert("Select Compnay Code First !");
$("#c_code").focus();
}
else if(p_code=="")
{
alert("Select Plant Code !");
$("#p_code").focus();
}
else if(po_no=="")
{
alert("Select Valied Po Number!");
$("#pur_order_no").focus();
}
else
{



       $.ajax({

           type:'POST',

           url:'{{ route("vendor.get_Material_Descrp") }}',

           data:{

            c_code:c_code,p_code:p_code,po_no:po_no,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data)
           {
              var default_plant='<option value="">--Select Material Code --</option>';
              var plant_opt="";
         
             if(data.count>0)
             {
              for(var i=0;i<data.count;i++)
              {
                //0109-048M0000(HANDLE MOULDED)
                plant_opt+='<option value="'+data.description[i].material_no+'">'+data.description[i].material_descrp+'</option>';
                 
                
              }
             }
             $("#mat_code_descrp").html(default_plant+plant_opt);
           }

        });
     }


}
</script>