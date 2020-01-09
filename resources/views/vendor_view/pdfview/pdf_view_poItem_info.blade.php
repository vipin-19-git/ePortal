       <table style="font-size: 12px;width: 100%;text-align: center;">
                <thead>
                    <tr>
                  <th>#</th>
                   <th >PO Number</th>
                    <th>Invoice No</th>
                    <th >Invoice Date</th>
                     <th>Material Code</th>
                    <th>Material Desc</th>
                   <th >Quantity</th>
                   <th>Dispatch Qty</th>
                  <th>Packing Qty</th>
                  
                  </tr>
                  </thead>
                  <tbody>
                    @php
                 $i=1;
                 @endphp
                 @foreach($dash_po_items as $po_items) 
                <tr>
                  <td>{{$i++}}</td>
                  <td>{{$po_items->PO_Number}}</td>
                   <td>{{$po_items->Invoice_No}}</td>
                     <td>{{$po_items->Invoice_Date}}</td>
                     <td>{{$po_items->Material}}</td>
                    <td>{{$po_items->Material_Desc}}</td>
                   <td>{{$po_items->Quantity}}</td>
                  <td>{{$po_items->Dispatch_Qty}}</td>
                 
                  <td>{{$po_items->Packing_Qty}}</td>
                
                 
              </tr>
              @endforeach
                 @if(count($dash_po_items)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="8">No any data</td>
                 
                </tr>
               @endif
                  </tbody>
                </table>