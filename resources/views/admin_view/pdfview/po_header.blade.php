 <table style="font-size: 12px;width: 100%;text-align: center;">
  <thead>
    <tr>
      <th>#</th>
      <th>Po Number</th>
      <th>Company Code</th>
      <th>PO category</th>
      <th>Vendor Code</th>
      <th>Vendor Name</th>
      <th>PO Org.</th>
      <th>PO Group</th>
      <th>Currency</th>
      </tr>
    </thead>
    <tbody>
       @php $i=1; @endphp
        @forelse($po_headers as $header)
        <tr>
        <td>{{ $i++}}</td>
        <td>{{ $header->PO_Number }}</td>
        <td>{{ $header->Company_Code }}</td>
        <td>{{ $header->PO_Category }}</td>
        <td>{{ $header->Vendor_Code }}</td>
        <td>{{ $header->Vendor_Name }}</td>
        <td>{{ $header->PO_Org }}</td>
        <td>{{ $header->PO_Group }}</td>
        <td>{{ $header->Currency }}</td>
       </tr>
         @empty
         <tr>
         <td style="text-align: center;color: red" colspan="6">No any data</td>
         </tr>
         @endforelse
       </tbody>
  </table>