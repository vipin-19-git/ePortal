 <table style="font-size: 12px;width: 100%;text-align: center;">
  <thead>
    <tr>
      <th>#</th>
      <th>Po Number</th>
      <th>Po Item No.</th>
      <th>Material</th>
      <th>Material Description</th>
      <th>Stock Quantity</th>
       <th>UOM</th>
    </tr>
    </thead>
    <tbody>
       @php $i=1; @endphp
        @forelse($po_details as $po_dtl)
        <tr>
                 <td>{{$i++}}</td>
                  <td>{{$po_dtl->po_number}}</td>
                  <td>{{$po_dtl->PO_Item_Code}}</td>
                  <td>{{$po_dtl->Material_Code}}</td>
                  <td>{{$po_dtl->Material_description}}</td>
                  <td>{{$po_dtl->PO_QTY}}</td>
                  <td>{{$po_dtl->UOM}}</td>
                </tr>
         @empty
         <tr>
         <td style="text-align: center;color: red" colspan="6">No any data</td>
         </tr>
         @endforelse
       </tbody>
  </table>