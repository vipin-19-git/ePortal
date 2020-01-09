@extends('layouts.suplier_master')

@section('content')
 
 
          <div class="page-header">
            <h3 class="page-title">
             Invoice Details ({{$y}}-{{ substr($y+1,2,2)}})
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Invoice Details</li>
              </ol>
            </nav>
          </div>

 <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"> Invoice Details</h4>
                 <!--  <p class="page-description">Add class <code>.table-striped</code> for table</p> -->
                  <div class="row">
                    <div class="table-responsive" id="printTable">
                            <div class="btn-group" style="float: right;" id="download_div">
                      <button type="button" class="btn btn-info">Download</button>
                      <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" id="dropdownMenuSplitButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton1">
                        <a class="dropdown-item"  onclick ="download_data('excel')">EXCEL</a>
                        <a class="dropdown-item"  onclick ="download_data('csv')">CSV</a>
                        <a class="dropdown-item"  onclick="download_data('pdf')">PDF</a>
                        <a class="dropdown-item"  onclick="download_data('print')">PRINT</a>
                        
                      </div>
                    </div>

                     <form action="{{route('vendor.export_invoice_gen')}}" method="post" id="main_form">
                    <input type="hidden" id="export_type" name="export_type">
                       @csrf
                     </form>
                      <table class="table  table-bordered" id="printable">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th >Invoice No </th>
                            <th >Invoice Date</th>
                            <th >PO Number</th>
                            <th >LR No</th>
                            <th >Transpoter Name</th>
                          </tr>
                        </thead>
                        <tbody>
                        @php
                        $cls="";
                 $i=1;
                 @endphp
                 @foreach($invoices as $invoice) 
               
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
                  <td>{{$invoice->Invoice_No}}</td>
                   <td>{{ date('d-M-Y',strtotime($invoice->Invoice_Date)) }}</td>
                  <td>{{$invoice->PO_Number}}</td>
                  <td>{{$invoice->LR_No}}</td>
                  <td>{{$invoice->Transpoter_Name}}</td>
                 
               </tr>
              @endforeach
                 @if(count($invoices)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="6">No any data</td>
                 
                </tr>
               @endif
                          
                        </tbody>
                      </table>
                    </div>

                  </div>
                     <div  style="float: right">
            {!! $invoices->render() !!}
          </div>
                </div>

              </div>
            </div>

@endsection

<script type="text/javascript">
 function download_data(type)
  {
    if(type!="print" && type!='')
    {
      $("#export_type").val(type);
     $("#main_form").submit();
    }
   if(type=="print")
    {
     var style= '<style type="text/css">table { page-break-inside:auto; width:100%} tr { page-break-inside:avoid; page-break-after:auto } thead { display:table-header-group } tfoot { display:table-footer-group } #download_div { display: none;} </style>';
     var content = $("#printTable").html();

     var mywindow = window.open('');
      mywindow.document.write('<html><head><title>Open vendor Query</title>');
      mywindow.document.write(style);
     mywindow.document.write('</head><body >');
     mywindow.document.write(content);
     mywindow.document.write('</body></html>');
     mywindow.document.close();
     mywindow.focus()
     mywindow.print();
     mywindow.close();
     return true;
  
   }
}

</script>