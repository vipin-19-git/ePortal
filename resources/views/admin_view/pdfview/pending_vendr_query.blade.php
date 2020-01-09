 <table style="font-size: 12px;width: 100%;text-align: center;">
  <thead>
   <tr>
    <th>#</th>
    <th>Vendor</th>
    <th>Date</th>
    <th>Topic</th>
    <th>Subject</th>
    <th>Message</th>
  </tr>
    </thead>
    <tbody>
      @php
                $i=1;
               @endphp
        @forelse($faqs as $faq)
        <tr>
          <td>{{$i++}}</td>
          <td style=" white-space: nowrap;">{{$faq->UserName}}</td>
          <td style=" white-space: nowrap;">{{date('d-M-Y',strtotime($faq->queryDate))}}</td>
          <td>{{$faq->topic}}</td>
          <td>{{$faq->subject}}</td>
          <td>{{$faq->message}}</td>
     
           
         
         @empty
         <tr >
    
            <td style="text-align: center;color: red" colspan="6">No any Query</td>
         </tr>
         @endforelse
       </tbody>
  </table>