@extends('layouts.suplier_master')

@section('content')
 
        <div class="page-header">
            <h3 class="page-title">
          Generate Barcode
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Generate Barcode</li>
              </ol>
            </nav>

          </div>

  <form  action="{{ route('vendor.generate_QCode')}}" method="post">
  

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
                  <h4 class="card-title">Generate Barcode</h4>
                     
                    @csrf
                
                     <div class="form-group row">
                       <label for="vendorCodelable" class="col-sm-3 col-form-label">Po Number</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="po_no"  name="po_no"  placeholder="PO Number" value="{{$po_headers->PO_Number}}" readonly="">
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Comapny Code</label>
                      <div class="col-sm-3">
                         <input type="text" class="form-control" id="c_code"  name="c_code"  value="{{ $po_headers->Company_Code}}" readonly="">
                      </div>
                    </div>
                         <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Po Category</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="po_categ"  name="po_categ" value="{{ $po_headers->PO_Category}}" readonly="">
                      </div>
                     <label for="vendorCodelable" class="col-sm-3 col-form-label">Purchasing Org.</label>
                      <div class="col-sm-3">
                         <input type="text" class="form-control" id="pur_org"  name="pur_org"  value="{{ $po_headers->PO_Org}}" readonly="">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Currency Key</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="po_currency"  name="po_currency" value="{{ $po_headers->Currency}}" readonly="">
                      </div>
                     <label for="vendorCodelable" class="col-sm-3 col-form-label">Plant</label>
                      <div class="col-sm-3">
                         <input type="text" class="form-control" id="po_plant"  name="po_plant"  value="{{ $po_headers->Plant_Code}}" readonly="">
                      </div>
                    </div>

                        <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Invoice No. </label>
                      <div class="col-sm-3" id="invoce_input_div">
                        <input type="text" class="form-control" id="invoice_no"  name="invoice_no" required=""  value="{{old('invoice_no')}}@if($inv=Session::get('inv')){{$inv}}@endif" @if($inv=Session::get('inv')) readonly="" @endif>
                        <p id="msg_inv">  </p>
                      </div>
                     <label for="vendorCodelable" class="col-sm-3 col-form-label">Invoice Date</label>
                    
                          <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control" name="invoice_date" id="datepicker" value="{{old('invoice_date')}} @if($dt=Session::get('dt')){{Date('m/d/Y',strtotime($dt))}} @endif" @if($dt=Session::get('dt')) readonly="" @endif>
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                         
                   
                  </div>
                
                            <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">LR No.</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="lr_no"  name="lr_no" required="" value="{{old('lr_no')}}@if($lr=Session::get('lr')){{$lr}}@endif" @if($lr=Session::get('lr')) readonly="" @endif>
                      </div>
                     <label for="vendorCodelable" class="col-sm-3 col-form-label">Transporter Name</label>
                      <div class="col-sm-3">
                         <input type="text" class="form-control" name="transporter" id="transporter" onkeypress="return alphaOnly(event)" required="" value="{{old('transporter')}}@if($trans=Session::get('trans')){{$trans}}@endif" @if($trans=Session::get('trans')) readonly="" @endif>
                      </div>
                    </div>
                   
                  
                </div>
              </div>
            </div>

              <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Dispatch Details</h4>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                           <th >#</th>
                  <th>Po Number</th>
                  <th>PO Item</th>
                  <th>Material</th>
                  <th>Material Description</th>
                   <th>UOM</th>
                  <th>Quantity</th>
                  <th>Dispatch Quantity</th>
                   <th>Packing Quantity</th>
                        </tr>
                      </thead>
                      <tbody>
                            @php
                $i=1;
                $j=0;
                @endphp
              @foreach($po_details as $details)
                        <tr>
                          <input type="hidden"   name="PO_No[]" value="{{ $details->po_number}}" > 
                           <input type="hidden"   name="PO_QTY[]" value="{{ $details->PO_QTY}}" > 
            <input type="hidden"   name="po_item[]" value="{{ $details->PO_Item_Code}}" >    
            <input type="hidden"  name="Mat_UOM[]" value="{{$details->UOM }}" >
           <input type="hidden"   name="Mat_Code[]" value="{{ $details->Material_Code }}" >
           <input type="hidden"  name="Mat_descrp[]" value="{{$details->Material_description }}" >
            <td style="width: 10px">{{ $i }}<input type="hidden"   name="img[]" value="{{ Auth::user()->VenderCode.'/'.$details->po_number.'/'.$details->Material_Code }}" > </td>
                  <td>{{ $details->po_number }}</td>
                  <td>{{ $details->PO_Item_Code }}</td>
                  <td>{{ $details->Material_Code}}</td>
                  <td>{{ $details->Material_description }}</td>
                  <td>{{ $details->UOM }}</td>
                  <td>
                    <input type="text" class="form-control" id="tot_qty_{{$i}}"  style="width: 100px;" name="tot_qty[]"   value="{{ $details->PO_QTY }}" readonly="" > 

                  </td>
                  <td> 
                    <input type="text"   class="form-control"  id="diapatch_qty_{{$i}}"  style="width: 100px;" name="diapatch_qty[]"  value="@if($disp=Session::get('disp')){{$disp[$j]}}@endif" @if($disp=Session::get('disp')) readonly="" @endif oninput="check_disp_qty({{$i}})" onkeypress="return event.charCode >= 48 && event.charCode <= 57">

                  </td>
                  <td> <input type="text" class="form-control" id="pack_qty_{{$i}}"  name="pack_qty[]" style="width: 100px;"  value="@if($pack=Session::get('pack')){{$pack[$j]}}@endif" @if($pack=Session::get('pack'))  readonly=""  @endif  oninput="check_pack_qty( {{ $i }} )" onkeypress="return event.charCode >= 48 && event.charCode <= 57">

                  </td>

                        </tr>
                 
                @php
                 $i=$i+1;
                 $j=$j+1;
                 @endphp
              @endforeach
           <tr>
                 <td colspan="9" style="text-align: center">

                   <button type="submit" class="btn btn-gradient-success btn-fw"  @if($bar_code_details = Session::get('bar_code_details')) disabled @endif name="gen_qr">Generate Barcode</button>
                 </td>
               </tr>
     
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
        </form>

@php
$i=1;

@endphp
  @if ($bar_code_details = Session::get('bar_code_details'))


 <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Barcode Results</h4>
                  
                  <div class="table-responsive">
                    <table  id="prinTQrCode">
                   
                      <tbody>
                          @foreach($bar_code_details as $code)
  @if($i==1)
  <tr>
    <td colspan="4">
      <img src="{{ url($code->Invoice_Bcode_Img_Name) }}"> <p style="margin-left: 50px;margin-top: -10px;"> {{ $code->Invoice_No }}</p>
    </td>
  </tr>
 @endif
  @if($i%4==1)
      <tr>
    @endif
       <td><p>Material No : {{ $code->Material }}</p>  <p>{{ $code->Material_Desc }}</p>
        <img src="{{ url($code->Barcode_Img_Name) }}"> <br><p>Quantity: {{ round($code->Packing_Qty).'/'. round($code->Dispatch_Qty)}}</p>
      </td>

   @if($i%4==0)
     </tr>
     @endif
    
@php
$i++;
@endphp
   @endforeach   
       <tr>
  <td colspan="4" style="text-align: center">
  <button type="button" class="btn btn-md btn-info" name="print_bar_code" onclick="printBarCode()"> Print</button>
  </td>
  </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            @endif

@endsection

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript">

function ImageReturne() {
   var table = $("#prinTQrCode");
   var new_table='<table>';
    table.find('tr').not(':last').each(function (i) {
    var x = $(this).find('td').each(function(j)
          {
          if(i!=0)
          {
           new_table+='<tr><td>'+$(this).find("p:first").text()+'<br>'+$(this).find("p:first").next().text() +'<br><img src="'+$(this).children('img').attr('src')+'">'+'<br>'+$(this).find("p:last").text()+'</td></tr>';
          }
          else
          {
            if(i%2==1)
            {
              new_table+="<tr>";
            }
           new_table+='<td>'+'<img src="'+$(this).children('img').attr('src')+'">'+'<br><p style="margin-left: 50px;margin-top: -20px;">'+$(this).find("p:first").text()+'</p></td>'; 
          if(i%2==0)
            {
              new_table+="</tr>";
            }
          }
         });
        });
    new_table+='</table>';
        
        var firstFunc = function step1(){ step2();}
        var secondFunc = function step2(){window.print();window.close()}

        var getImages = "<html><head><script>\n" + firstFunc + "\n" + secondFunc + "</scri" + "pt></head><body onload='step1()'>\n" +
        "" + new_table + "" + "</body></html>";
       console.log(getImages);
        return getImages;
    }


    function printBarCode() {
        Pagelink = "about:blank";
        var pwa = window.open(Pagelink);
        pwa.document.open();
        pwa.document.write(ImageReturne());
        pwa.document.close();
    }


  function check_disp_qty(seq)
  {
    var tot_qty=parseInt($("#tot_qty_"+seq).val());
    var disp_qty=parseInt($("#diapatch_qty_"+seq).val());
     if(tot_qty!='')
     {
     
      if(disp_qty>tot_qty)
      {
      alert("Dispatch quantity should be less than total quantity");
       $("#diapatch_qty_"+seq).val('');
      $("#diapatch_qty_"+seq).focus();
      }
    }
    else
    {
     alert("Material quantity must have some data"); 
    }
  }
  function check_pack_qty(seq)
  {
   var disp_qty=parseInt($("#diapatch_qty_"+seq).val());
   var pack_qty=parseInt($("#pack_qty_"+seq).val());
   if(disp_qty!='')
   {
    if(pack_qty>disp_qty)
    {
      alert("Packing quantity should be less than dispatch quantity");
       $("#pack_qty_"+seq).focus();
        $("#pack_qty_"+seq).val('');
    }
  }
  else
  {
     alert("Dispatch quantity must have some value");
      $("#pack_qty_"+seq).val('');
     $("#diapatch_qty_"+seq).focus();
  }
  }


  function check_validate(no)
   {
   
      var flag=false;
      var which="";
      var k=1;
      var invoice_nos=$("#invoice_no").val().trim();
      var invoice_dates=$("input[name=invoice_date]").val().trim();
      var lr_nos=$("#lr_no").val().trim();
      var transporters=$("#transporter").val().trim();
      
     if(invoice_nos=='')
     {
       alert("Invoice Number is required");
       $("#invoice_no").focus();
        return   flag=false;
     }
    else if(invoice_dates=='')
    {
       alert("Invoice Date is required");
        $("input[name=invoice_date]").focus();
        return   flag=false;
     }
    else if(lr_nos=='')
    {
     alert("LR number is required");
      $("#lr_no").focus();
      return   flag=false;
    }
    else  if(transporters=='')
    {
     alert("Transporter name is required");
      $("#transporter").focus();
  return    flag=false;
    }
  
  else
  {


      for(var i=1;i<=no;i++)
      {
         var disp_qty=($("#diapatch_qty_"+i).val());
         var pack_qty=($("#pack_qty_"+i).val());
         if(disp_qty!='' && pack_qty!='')
          {
             flag=true;
          }
        else
         {
          
            if(disp_qty=='' && pack_qty!='')
            {
             which="#diapatch_qty_"+i;
             flag=false;
             
            }
           if(disp_qty!='' && pack_qty=='')
           {
             which="#pack_qty_"+i;
             flag=false;
             
           }
           k++; 
        }   
     }
     
     if(k==i )
     {
      if($("#diapatch_qty_1").val()=='')
      {
         alert("Please Fill At Least One Of The Row");
         $("#diapatch_qty_1").focus();
         return flag=false;
      
      }
      else
      {
         alert("Please Fill At Least One Of The Row");
         $("#pack_qty_1").focus();  
         return flag=false;
      }
     
     }
     else
     {
      $(which).focus();
     }

   } 
//return false;
     return flag;
  }
function check_invoice_no()
{
   var invoice_no = $("input[name=invoice_no]").val();
   
  
    $.ajax({

           type:'POST',

           url:'{{ route("vendor.is_available_invoice") }}',

           data:{

            invoice_no:invoice_no,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data){
          
             if(data.success)
             {
                return true; 
             }
             else
             {
            
              
               return false;  
              
             }
           }

        });
}



/*
  function is_Invoice_Available()
  {
     var invoice_no = $("input[name=invoice_no]").val();
       $.ajax({

           type:'POST',

           url:'{{ route("vendor.is_available_invoice") }}',

           data:{

            invoice_no:invoice_no,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data){
           
             if(data.success)
             {
               $("#invoce_input_div").removeClass("has-error");
               $("#invoce_input_div").addClass("has-success");
              $("#msg_inv").html('<label class="alert alert-success control-label" role="alert" for="inputSuccess"><i class="fa fa-check"></i>Available !</label>')
             }
             else
             {
              var label='<label  class="alert alert-danger control-label" for="inputError">'+
              '<i class="fa fa-times-circle-o"></i> Not Available !</label>';
              $("#invoce_input_div").removeClass("has-success");
              $("#invoce_input_div").addClass("has-error");
              $("#msg_inv").html(label);
              
               
              
             }
           }

        });
}*/


  function alphaOnly(event)
   {
   var key = event.keyCode;

   return ((key >= 65 && key <= 90) || key == 8  || key == 32 || (key >= 97 && key <= 122));
}
</script>