 <table style="font-size: 12px;width: 100%;text-align: center;">
  <thead>
    <tr>
      <th>#</th>
      <th>Notifications</th>
      <th>Entry date</th>
      <th>Valied Upto</th>
      </tr>
    </thead>
    <tbody>
       @php $i=1; @endphp
        @forelse($notifications as $notes)
          <tr>
           <td>{{$i++}}</td>
           <td>{{$notes->notification}}</td>
           <td>{{$notes->entry_date}}</td>
           <td>{{ $notes->valied_upTo}}</td>
         </tr>
         @empty
         <tr>
         <td style="text-align: center;color: red" colspan="6">No any Query</td>
         </tr>
         @endforelse
       </tbody>
  </table>