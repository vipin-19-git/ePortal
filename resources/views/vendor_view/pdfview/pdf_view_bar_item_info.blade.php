 <table style="font-size: 12px;width: 100%;text-align: center;">
                  <thead>
                    <tr>
                  <th>#</th>
               
                   <th >Comp Code</th>
                   <th>Plant Code</th>
                    <th >Po No</th>
                     <th >Invoice No.</th>
                   <th>Mat Descrp</th>
                   <th>Stk Qty</th>
                   <th>Dispatch Qty</th>
                 
                 </tr>
                  </thead>
                  <tbody>
                   @php
                 $i=1;
                 @endphp
                 @foreach($data as $po_dtl) 
                <tr>
                  <td>{{$i++}}</td>
                  
                  <td>{{$po_dtl->Company_Code}}</td>
                  <td>{{$po_dtl->Plant_Code}}</td>
                  <td>{{$po_dtl->PO_Number}}</td>
                   <td>{{$po_dtl->Invoice_No}}</td>
                  <td>{{$po_dtl->Material_Desc}}</td>
                  <td>{{$po_dtl->Quantity}}</td>
                 
                   <td>{{$po_dtl->Dispatch_Qty}}</td>
              </tr>
              @endforeach
                 @if(count($data)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="6">No any data</td>
                 
                </tr>
               @endif
                  </tbody>
                </table>