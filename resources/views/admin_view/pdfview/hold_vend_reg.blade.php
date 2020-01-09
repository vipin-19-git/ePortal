 <table style="font-size: 12px;width: 100%;text-align: center;">
  <thead>
  <tr>
          <th>#</th>
          <th>Vendor Name</th>
          <th>Business Type</th>
           <th>Mobile</th>
          <th>Email</th>
          <th>Phone No.</th>
         <th>Address</th>
          <th>GST No.</th>
          <th>Pan No.</th>
    </thead>
    <tbody>
      @php
                $i=1;
            $cntry="";$st="";$cty="";
               @endphp
        @forelse($vendors as $vendor )
      @if($vendor && $vendor->country_name)
      @php $cntry=$vendor->country_name;  @endphp
      @endif
       @if($vendor && $vendor->state_name)
      @php  $st=$vendor->state_name;  @endphp
      @endif
      @if($vendor && $vendor->city_name)
     @php  $cty=$vendor->city_name;  @endphp
      @endif
        <tr>
          <td>{{$i++}}</td>
          <td>{{$vendor->vendor_name}}</td>
          <td> {{ $vendor->name }}</td>
           <td>{{$vendor->mobile}}</td>
           <td>{{ $vendor->email}}</td>
           <td>{{ $vendor->phone_no}}</td>
           <td>
           @if( $vendor->address_1!= "")
          {{ $vendor->address_1."," }}
          @endif

          @if( $vendor->address_2!= "")
          {{ $vendor->address_2."," }}
          @endif

          @if( $vendor->address_3!= "")
          {{ $vendor->address_1."," }}
          @endif

          @if($cty!= "")
          {{ $cty."," }}
          @endif
        @if($st!= "")
          {{ $st."," }}
          @endif
          @if( $cntry!= "")
          {{ $cntry }}
          @endif

   


           </td>
          <td>{{ $vendor->gst_no}}</td>
          <td>{{ $vendor->pan_no}}</td>


        </tr>
         @empty
         <tr>
    
            <td style="text-align: center;color: red" colspan="9">No any data</td>
         </tr>
         @endforelse
       </tbody>
  </table>