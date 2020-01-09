           @php $i=1; @endphp
  
            @foreach($sch_items as $po_dtl) 
             <tr style="text-align: center;">

              <td >{{$i++}}</td>
              <td>
              <a href="{{ route('vendor.qrcode') }}" onclick="event.preventDefault();
                                                     document.getElementById({{$i}}).submit();">{{$po_dtl->PO_Number}} </a>
              <form action="{{ route('vendor.qrcode') }}" method="post" id="{{$i}}">
                          @csrf
                <input type="hidden" name="gen_qr" value="{{$po_dtl->PO_Number}}" >
                </form>
                </td>
                 <td>{{$po_dtl->PO_QTY  }}</td>
                 <td>{{$po_dtl->Company_Code }}</td>
                  <td>{{$po_dtl->Plant_Code}}</td>
                  <td>{{$po_dtl->Material_description}}</td>
              </tr>
              @endforeach
              @if(count($sch_items)==0)
                <tr>
                  <td style="text-align: center;color: red" colspan="6">No any matches</td>
                </tr>
               @endif