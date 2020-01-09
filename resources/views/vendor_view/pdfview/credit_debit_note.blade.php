  <table style="font-size: 9px;width: 100%;text-align: center " >
    <tr>
      <th colspan="15"> Credit Debit Note </th>
    </tr>
             <tr>
          <th>#</th>
          <th>Material Documents</th>
          <th>Material Documents Year</th>
           <th>Line Item</th>
          <th>Posting Date</th>
          <th>Material Code</th>
          <th>Material Desc</th>
          <th>Vendor Code</th>
          <th>Vendor Name</th>
           <th>Quantity</th>
          <th>Quantity Type</th>
          <th>Invoice No</th>
          <th>Invoice Doc Year</th>
          <th>Qty</th>
          <th>Delivery Note</th>
          <th>Rejected Short Qty</th>
          
        </tr>
         @php
                $i=1;
               @endphp

               @if(!empty($credit_debit_notes))
                @foreach($credit_debit_notes as $note)
              <tr>
          <td>{{$i++}}</td>
           <td>{{$note->MatDoc}}</td>
           <td>{{$note->MatDocYr}}</td>
            <td>{{$note->LineItem}}</td>
          <td>{{ date('d-M-Y',strtotime($note->PostingDate))}}</td>
          <td>{{$note->MaterialCode}}</td>
           <td>{{$note->MaterialDesc}}</td>
          <td>{{$note->VendorCode}}</td>
           <td>{{$note->VendorName}}</td>
           <td>{{$note->Quantity}}</td>
           <td>{{$note->QuantityType}}</td>
            <td>{{$note->Invoice_No}}</td>
            <td>{{$note->InvDocYr}}</td>
             <td>{{$note->Qty}}</td>
             <td>{{$note->DeliveryNote}}</td>
             <td>{{$note->Rejected_Short_Qty}}</td>
            
        
        </tr>
                @endforeach
                
                @else
                <tr>
                  <td style="text-align: center;color: red" colspan="15">No any data</td>
                 
                </tr>
               @endif           
</table>
