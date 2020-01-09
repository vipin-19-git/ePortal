 <html>
 <body>


 <table style="font-size: 12px;width: 100%;text-align: center;">
 
                  <thead>
                    <tr>
                  <th>#</th>
 	               <th >PO Number</th>
                    <th>Avl Qty</th>
                    <th>Material Code Desc</th>
                   <th >Supplier Name</th>
                   <th>Supplier Cont Person</th>
                  
                   <th>From Date</th>
                  <th >To Date</th>
                  
                 <th>Reason</th>

                 </tr>
                  </thead>
                  <tbody>
                    @php
                 $i=1;
                 @endphp
                 @foreach($delivery_delay as $delay) 
                <tr>
                  <td>{{$i++}}</td>
                  <td>{{$delay->PO_Number}}</td>
                   <td>{{$delay->Avl_Qty}}</td>
                    <td>{{$delay->Material_Code_Desc}}</td>
                   <td>{{$delay->Supplier_Name}}</td>
                  <td>{{$delay->Supplier_Cont_Person}}</td>
                 
                  <td>{{$delay->From_Date}}</td>
                  <td>{{$delay->To_Date}}</td>
                 
                  <td>{{$delay->Reason}}</td>
              </tr>
              @endforeach
                 @if(count($delivery_delay)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="6">No any data</td>
                 
                </tr>
               @endif
                  </tbody>
                </table>
              </body>
              </html>
