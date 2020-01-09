  <table style="font-size: 10px;width: 100%;text-align: center " >
    <tr>
                  <th>#</th>
                  <th>Vendor Inv. No</th>
                  <th>Doc. Date</th>
                  <th>Vendor Code</th>
                  <th>Vendor Name</th>
                   <th>HSN Code</th>
                  <th>Material</th>
                  <th>Description</th>
                  <th>Quantity </th>
                  <th>Unit Price</th>
                  <th>Inv. Basic Amt.</th>
                  <th>Accounting Doc. No.</th>
                  <th>Posting Date</th>
                  <th>CGST(%)</th>
                  <th>SGST (%)</th>
                  <th>IGST (%)</th>
                  <th>CGST Value</th>
                  <th>SGST Value</th>
                  <th>IGST Value</th>
                  <th>GRN No.</th>
                  <th>Fiscal Year</th>
                  <th>PO Number</th>
                  <th>Line Item No.</th>
                  <th>Plant</th>
                  <th>Tax Code</th>
                </tr>
               @if(!empty($material_returns))
                @foreach($material_returns as $material)
              <tr>
           
                  <td>{{ $i++}}</td>
                  <td>{{ $material['VendorInvNo'] }}</td>
                  <td>{{ $material['docDate'] }}</td>
                  <td>{{ $material['VendorCode'] }}</td>
                  <td>{{ $material['VendorName'] }}</td>
                  <td>{{ $material['HSNCode'] }}</td>
                  <td>{{ $material['Material'] }}</td>
                  <td>{{ $material['Description'] }}</td>
                  <td>{{ $material['Quantity'] }}</td>
                  <td>{{ $material['UnitPrice'] }}</td>
                  <td>{{ $material['InvBasicAmt'] }}</td>
                  <td>{{ $material['AccountingDocNo'] }}</td>
                  <td>{{ $material['PostingDate'] }}</td>
                  <td>{{ $material['CGSTPer'] }}</td>
                  <td>{{ $material['SGSTPer'] }}</td>
                  <td>{{ $material['IGSTPer'] }}</td>
                  <td>{{ $material['CGSTValue'] }}</td>
                  <td>{{ $material['SGSTValue'] }}</td>
                  <td>{{ $material['IGSTValue'] }}</td>
                  <td>{{ $material['GRNNo'] }}</td>
                  <td>{{ $material['FiscalYear'] }}</td>
                  <td>{{ $material['PONumber'] }}</td>
                  <td>{{ $material['LineItemNo'] }}</td>
                  <td>{{ $material['Plant'] }}</td>
                  <td>{{ $material['TaxCode'] }}</td>
                
                </tr>
                @endforeach
                
                @else
                <tr>
                  <td style="text-align: center;color: red" colspan="25">No any data</td>
                 
                </tr>
               @endif           
</table>
