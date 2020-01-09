   <!DOCTYPE html>
   <html>
   <head>
    <style type="text/css">
       @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
        </style>
    </head>
     
   </head>
   <body>

   <table style="font-size: 10px" >
                <tr>
                  <th>#</th>
                  <th>Vendor code</th>
                  <th>Vendor Name</th>
                  <th>Plant</th>
                  <th>Invoice no.</th>
                   <th>Invoice Date</th>
                  <th>GRN No.</th>
                  <th>GRN Line item</th>
                   <th>GRN Date</th>
                  <th>Part Code</th>
                  <th>Part Description</th>
                  <th>Quantity</th>
                  <th>UOM</th>
                  <th>QC Lot Num</th>
                  <th>QC Date</th>
                  <th>QC Accepted Qty</th>
                  <th>QC Rejected Qty</th>
                </tr>
               @php
                 $i=1;
                 @endphp
                @foreach($invoice_status as $invoice)
              <tr>
                  <td>{{ $i++}}</td>
                  <td>{{ $invoice['vendCode'] }}</td>
                  <td>{{ $invoice['vendName'] }}</td>
                  <td>{{ $invoice['plant'] }}</td>
                  <td>{{ $invoice['invoiceNo']}}</td>
                  <td>{{ $invoice['invoiceDate'] }}</td>
                  <td>{{ $invoice['grnNo'] }}</td>
                  <td>{{ $invoice['grnLineItem'] }}</td>
                  <td>{{ $invoice['grnDate'] }}</td>
                  <td>{{ $invoice['partCode'] }}</td>
                  <td>{{ $invoice['partDescription'] }}</td>
                  <td>{{ $invoice['quantity'] }}</td>
                  <td>{{ $invoice['UOM'] }}</td>
                  <td>{{ $invoice['QcLotNum'] }}</td>
                  <td>{{ $invoice['QcDate'] }}</td>
                  <td>{{ $invoice['QcAcceptedQty'] }}</td>
                  <td>{{ $invoice['QcRejectedQty'] }}</td>
                </tr>
                @endforeach
                @if(count($invoice_status)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="9">No any data</td>
                 
                </tr>
               @endif
              </table>
   </body>
   </html>
    