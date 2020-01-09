       <table style="font-size: 12px;width: 100%;text-align: center;">
                  <thead>
                    <tr>
                  <th>#</th>
                  <th>Invoice No</th>
                  <th >Invoice Date</th>
                   <th >PO Number</th>
                  <th>LR No</th>
                   <th >Transpoter Name</th>
                   
                  </tr>
                  </thead>
                  <tbody>
                    @php
                 $i=1;
                 @endphp
                 @foreach($invoices as $invoice) 
                <tr>
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