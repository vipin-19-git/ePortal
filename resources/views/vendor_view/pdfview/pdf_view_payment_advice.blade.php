
           <table style="font-size: 12px;width: 100%;text-align: center" >
                <tr>
                  <th>#</th>
                  <th>Clearing Aug Date</th>
                  <th>Amount</th>
                  <th>Currency</th>
                  <th>Document No</th>
                 
                  
                </tr>
                  @if(!empty($payment_advice))
                @foreach($payment_advice as $advice)
              @php  $i=0; @endphp
              <tr>
           
                  <td>{{ $i++}}</td>
                  <td>{{ $advice['ClearingAugDate'] }}</td>
                  <td>{{ $advice['Amount'] }}</td>
                  <td>{{ $advice['Currency'] }}</td>
                  <td>{{ $advice['DocumentNo'] }}</td>
        
                
                </tr>
                @endforeach
               @else
                <tr>
                  <td style="text-align: center;color: red" colspan="6">No any data</td>
                 
                </tr>
               @endif
              </table>
