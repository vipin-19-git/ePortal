 <table style="font-size: 12px;width: 100%;text-align: center;">
                  <thead>
                   <tr>
                  <th>#</th>
                  <th>PO Number</th>
                  <th> PO Item</th>
                  <th >Material</th>
                  <th>Material Description</th>
                   <th >UOM</th>
                   <th>Stock Quantity</th>
                   <th>Dispatch Quantity</th>
                   <th>Packing Quantity </th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                 $i=1;
                 @endphp
                 @foreach($po_details as $po_detail) 
                <tr>
                   <td>{{$i++}}</td>
                   <td>{{$po_detail->PO_Number}}</td>
                   <td>{{$po_detail->PO_Item}}</td>
                   <td>{{$po_detail->Material}}</td>
                   <td>{{$po_detail->Material_Desc}}</td>
                   <td>{{$po_detail->UOM}}</td>
                   <td>{{$po_detail->Quantity}}</td>
                   <td>{{$po_detail->Dispatch_Qty}}</td>
                    <td>{{$po_detail->Packing_Qty}}</td>
               </tr>
              @endforeach
                 @if(count($po_details)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="9">No any data</td>
                 
                </tr>
               @endif
                  </tbody>
                </table>