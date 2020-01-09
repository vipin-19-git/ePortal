@extends('layouts.suplier_master')

@section('content')
 <style type="text/css">
   

 </style>
        <div class="page-header">
            <h3 class="page-title">
Print/Re-Print Barcode
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Print/Re-Print Barcode</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Print/Re-Print Barcode</h4>
               
                  <form action="{{ route('vendor.get_generated_bar_code')}}" method="post">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Search By:</label>
                      <div class="form-check form-check-info col-sm-3">
                      <label class="form-check-label">
                       <input type="radio" class="form-check-input " name="get_code_by" id="get_code_by" value="1" checked  onclick="get_form(this.value)">
                             Invoice Barcode
                            </label>
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label"></label>
                      <div class="form-check form-check-info col-sm-3">
                   <label class="form-check-label">
                    <input type="radio" class="form-check-input "  name="get_code_by" id="get_code_by" value="2"   onclick="get_form(this.value)">
                             Missing Barcode
                            </label>
                      </div>
                    </div>
               <input type="hidden" id="type_of_get_bcode" name="type_of_get_bcode" value="1">
               <div class="form-group row" id="by_inv_bar_cd">
                       <label for="vendorCodelable" class="col-sm-3 col-form-label" >Invoice Barcode No </label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control "  style="height: 62%;margin-left: -53px;" id="invoice_barcode"  name="invoice_barcode"  
           value="{{old('invoice_barcode')}}@if(isset($type_serach) && $type_serach==1 && $invoice_barc!=''){{ $invoice_barc }} @endif" required="">
                      </div>
                     
                    </div>
                    <div class="form-group row" style="display: none "  id="by_missing_bar_cd">
                       <label for="vendorCodelable" class="col-sm-3 col-form-label">PO Number</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control " style="height: 62%;margin-left: -53px;"  id="invoice_po"  name="invoice_po"  class="form-control input-sm " value="{{old('invoice_po')}}@if(isset($type_serach) &&  $type_serach==2 && $invoice_po!=''){{$invoice_po}} @endif" >
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Invoice Date :</label>
                      <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                         <input type="text" class="form-control "  style="height: 75%;margin-left: -53px;"  name="invoice_date"  id="datepicker"  value="{{old('invoice_date')}}@if(isset($type_serach) && $type_serach==2 && $invoice_date!='') {{$invoice_date}}@endif">
                         <span class="input-group-addon input-group-append border-left" style="height: 36px;" >
                          <span class="mdi mdi-calendar input-group-text"></span>
                      </div>
                    </div>
                
                    <button type="submit" class="btn btn-gradient-primary mr-2">Print/Reprint Barcode</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
 
@php
                $i=1
                @endphp
    @if(!empty($type_serach))

 <div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
   <div class="card-body">
    <h4 class="card-title">Reprint Invoice Barcode</h4>
      @if($type_serach==1)
      <div class="table-responsive">
       <table  id="prinTQrCode">
        <tbody>
        @foreach($gen_bcode_details as $code)
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
    @if(count($gen_bcode_details)!=0)
       <tr>
  <td colspan="4" style="text-align: center">
  <button type="button" class="btn btn-md btn-info" name="print_bar_code" onclick="printBarCode()"> Print</button>
  </td>
  </tr>
   @endif

                      </tbody>
                    </table>
                  </div>
                   @endif
 @if($type_serach==2)
                          <div class="table-responsive">
                      <table class="table  table-bordered">
                        <thead>
                         <tr>
                         <th>#</th>
                  <th>Invoice Number</th>
                  <th>Po Number</th>
                  <th>Company Code</th>
                  <th>Invoice Date</th>
                  <th>Create Date</th>
                       </tr>
                        </thead>
                        <tbody>
                        @php
                        $cls="";
                 $i=1;
                 @endphp
                  @foreach($gen_bcode_details as $deatils)
               
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
                   <td>{{ $deatils->Invoice_No }}</td>
                  <td>{{ $deatils->PO_Number }}</td>
                  <td>{{ $deatils->Company_Code }}</td>
                  <td>{{ date('d-M-Y',strtotime($deatils->Invoice_Date))}}</td>
                  <td>{{ date('d-M-Y',strtotime($deatils->Entry_Date))}}</td>
                 
               </tr>
              @endforeach
                  @if(count($gen_bcode_details)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="6">No any data</td>
                 
                </tr>
               @endif
                          
                        </tbody>
                      </table>
        
                    </div> 

                     @endif
                </div>
              </div>
            </div>
           
 @endif
@endsection


<script type="text/javascript">
function ImageReturne() {
   var table = $("#prinTQrCode");
    
   var new_table='<table style="overflow: hidden;">';
    table.find('tr').not(':last').each(function (i) {
    var x = $(this).find('td').each(function(j)
          {
          
          if(i!=0)
          {
           new_table+='<tr><td>'+$(this).find("p:first").text()+'<br>'+$(this).find("p:first").next().text() +'<br><img src="'+$(this).children('img').attr('src')+'" height="37px" width="37px">'+'<br>'+$(this).find("p:last").text()+'</td></tr>';
          }
          else
          {
           new_table+='<tr><td>'+'<img src="'+$(this).children('img').attr('src')+'"  height="37px" width="37px">'+'<br><p style="margin-left: 50px;margin-top: -20px;">'+$(this).find("p:first").text()+'</p></td></tr>'; 
          }
        
         });
        });
    new_table+='</table>';

        var firstFunc = function step1(){ step2();}
        var secondFunc = function step2(){window.print();window.close()}

        var getImages = "<html><style>body{ margin: 25mm 25mm 25mm 25mm;}</style> <head><script>\n" + firstFunc + "\n" + secondFunc + "</scri" + "pt></head><body onload='step1()'>\n" +
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


function get_form(which)
{
   
 if(which==1)
 {

  $("#by_inv_bar_cd").show();
  $("#by_missing_bar_cd").hide();
  $('#invoice_barcode').prop('required',true);
     if($('#invoice_po').prop('required'))
     {
      $('#invoice_po').removeAttr('required');
     } 
 
  if($('#datepicker').prop('required'))
     {
      $('#datepicker').removeAttr('required');
     } 
 
   $('#invoice_po').val('');
 $('#datepicker').val('');
 }
 if(which==2)
 {
   
  $("#by_missing_bar_cd").show();
  $("#by_inv_bar_cd").hide();
  $('#invoice_po').prop('required',true);
  $('#datepicker').prop('required',true);
   if($('#invoice_barcode').prop('required'))
     {
     $('#invoice_barcode').removeAttr('required');
     } 
   
   $('#invoice_barcode').val('');
  
 }
 $("#type_of_get_bcode").val(which);
  $("#print_option").show();
}
</script>