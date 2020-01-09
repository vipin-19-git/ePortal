@extends('layouts.admin_master')

@section('content')
 
 
          <div class="page-header">
            <h3 class="page-title">
             Queries
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Queries</li>
              </ol>
            </nav>
          </div>
                   <div class="col-md-12 grid-margin stretch-card" id="askfaq" style="@if (! $errors->any())display: none @endif">
              <div class="card">

                <div class="card-body"  >
                  <h4 class="card-title">Update Query Status</h4>
       
            <form method="post" action="{{ route('admin.vendor_query_ans') }}" id="askfaq">
           @csrf  
        <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Vendor Name</label>
             <div class="col-sm-9">
             <input type="text" class="form-control" name="vendor_name" id="vendor_name" value="{{old('vendor_name')}}" readonly="">
           </div>
        </div>

          <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Topic</label>
             <div class="col-sm-9">
              <input type="text" class="form-control" name="topic" id="topic" value="{{old('topic')}}" readonly="">
         
           </div>
        </div>

       <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Subject</label>
             <div class="col-sm-9">
               <input type="text" class="form-control"  name="subject" id="subject" value="{{old('subject')}}"  readonly="">
           </div>
        </div>
          <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Message</label>
             <div class="col-sm-9">
                
              <textarea  class="form-control" rows="10" cols="52" name="message" id="message"  readonly=""  style="resize: none;" readonly="" >{{old('message')}}
             </textarea>
           </div>
        </div>

       <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Action</label>
             <div class="col-sm-9">
                <select class="form-control" id="action" name="action"  class="form-control" >

          <option value="">-Select Status--</option>
          <option value="Resolved" @if(old('topic')=="Bill Related") selected="" @endif >Resolved</option>
          <option value="Hold" @if(old('topic')=="Schedule Related") selected="" @endif >Hold</option>
          <option value="Cancel" @if(old('topic')=="Po Related") selected="" @endif >Cancel</option>
          
        </select>
           </div>
        </div>
           <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Message</label>
             <div class="col-sm-9">
                
              <textarea  class="form-control" rows="10" cols="52" name="remarks" style="resize: none;">{{old('remarks')}}
             </textarea>
           </div>
        </div>
         <div class="form-group row">
         <div class="col-sm-4">
          <input type="hidden" class="form-control pull-right"  name="query_id" id="query_id">
         </div>
         <div class="col-sm-5">
         
         <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <button class="btn btn-light" type="reset" onclick="cancle_faq()">Cancel</button>
                  
               </div>   
                  <div class="col-sm-3">
         </div>
       </div>
        
 </form>
      </div>
    </div>
  </div>

             <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">  Query Details</h4>
                 <!--  <p class="page-description">Add class <code>.table-striped</code> for table</p> -->
                  <div class="row">
                    <div class="table-responsive" id="printTable">
           
                       <form action="{{route('admin.exprt_pndng_qry')}}" method="post" id="main_form">
                    <input type="hidden" id="export_type" name="export_type">
                       @csrf
                     </form>
                      <table class="table  table-bordered" >
                        <thead>
                         <tr>
                           <th>#</th>
                           <th>vendor</th>
                           <th>Date</th>
                           <th>Topic</th>
                           <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                           <th>Action</th>
                         </tr>
                        </thead>
                        <tbody>
                        @php
                        $cls="";
                 $i=1;
                 @endphp
                  @forelse($faqs as $faq)
               
                 @if($i%4==0)
                 @php $cls="table-info"; @endphp
                 @elseif($i%4==1)
                  @php  $cls="table-warning"; @endphp
                  @elseif($i%4==2)
                   @php  $cls="table-success"; @endphp
                   @elseif($i%4==3)
                   @php  $cls="table-primary"; @endphp
                   @endif
                
                   <tr class="{{ $cls }}">
                    <td>{{$i++}}</td>
                      <td style=" white-space: nowrap;">{{$faq->UserName}}</td>
                    <td  style=" white-space: nowrap;">{{date('d-M-Y',strtotime($faq->queryDate))}}</td>
                    <td>{{$faq->topic}}</td>
                     <td>{{$faq->subject}}</td>
                     <td>{{$faq->message}}</td>
                       <td>
         @if(trim($faq->status)=='open')
          
             <div class="badge badge-gradient-info">
          @endif
          @if(trim($faq->status)=='hold')
          
             <div class="badge badge-gradient-warning">
          @endif
         @if(trim($faq->status)=='cancel')
         <div class="badge badge-gradient-danger">
          @endif
          @if(trim($faq->status)=='resolved')
          <div class="badge badge-gradient-success">
         
             @endif
           {{ ucfirst(strtolower($faq->status))}}</div></td>
            
     
          <td>
            @if($faq->status=="open" || $faq->status=="hold")<button type="button" class="btn btn-sm btn-gradient-primary btn-rounded " onclick="show_hideForm({{$faq->id}})" name="sol_{{$faq->id}}" id="sol_{{$faq->id}}" >Solution</button> @endif</td>
               </tr>
               
         @empty
         <tr >
    
            <td style="text-align: center;color: red" colspan="6">No any Query</td>
         </tr>
          @endforelse
                          
                        </tbody>
                      </table>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
 function show_hideForm(id)
  {

    //$("#askfaq").fadeToggle('slow');
    $("#askfaq").show(2000);
  // $("#sol_"+id).hide();
    $("#query_id").val(id);
 
       $.ajax({

           type:'POST',

           url:'{{ route("admin.get_queryData") }}',

           data:{

            faq_id:id,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data)
           {
                   $("#vendor_name").val(data.UserName);
                   $("#topic").val(data.topic);
                   $("#subject").val(data.subject);
                   $("#message").val(data.message);
           }

        });

  }
  function Qryfilter_by_status(val)
  {

    $("#qryListStatus").submit();
  

  }
function reset_form_cancel()
{
$("#qryFrm")[0].reset();
 $("#askfaq").hide(2000);

}

</script>