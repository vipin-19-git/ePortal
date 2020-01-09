@extends('layouts.suplier_master')

@section('content')
 
        <div class="page-header">
            <h3 class="page-title">
        Ask Query
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ask Query</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card" id="askfaq" style="@if (! empty($faqs))display: none @endif">
              <div class="card">

                <div class="card-body"  >
                  <h4 class="card-title">Ask Query</h4>
       
            <form method="post" action="{{ route('vendor.post_ask_query') }}" id="qryFrm">
           @csrf  
          <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Topic</label>
             <div class="col-sm-9">
              <select class="form-control" id="topic" name="topic"  required="">
             <option value="">-Select Topic--</option>
             <option value="Bill Related" @if(old('topic')=="Bill Related") selected="" @endif >Bill Related</option>
             <option value="Schedule Related" @if(old('topic')=="Schedule Related") selected="" @endif >Schedule Related</option>
             <option value="Po Related" @if(old('topic')=="Po Related") selected="" @endif >Po Related</option>
             <option value="Others" @if(old('topic')=="Others") selected="" @endif >Others</option>
           </select>
           </div>
        </div>

       <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Subject</label>
             <div class="col-sm-9">
               <input type="text" class="form-control" name="subject" id="subject" value="{{old('subject')}}">
           </div>
        </div>
          <div class="form-group row">
             <label for="vendorCodelable" class="col-sm-3 col-form-label">Message</label>
             <div class="col-sm-9">
    <textarea  class="form-control" rows="10" cols="52" name="message"  style="resize: none;">{{old('message')}}</textarea>
           </div>
        </div>
         <div class="form-group row">
         <div class="col-sm-4">
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
           


         
      <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Query Details</h4>

                         <div class="row" id="alert_msg">
                  <div class="col-sm-7">
                 </div>
              @if ($message = Session::get('success'))
              
              <div class="col-sm-5 alert alert-success" role="alert" >
                     {{ $message }}
                  </div>
              
                 @endif
                    @if ($message = Session::get('error'))
                          
                    <div class="col-sm-5 alert alert-danger" role="alert" >

                     {{ $message }}
                
                </div>
                    @endif
                    @if ($errors->any())
                  
                    <div class="col-sm-5 alert alert-danger" role="alert">
                    {{ $errors->first() }}
                  </div>
                 @endif
               </div>
                  <div class="row">
                    
                    <div class="table-responsive">
         
                      <button type="button" class="btn btn-gradient-warning btn-rounded btn-icon" style="float: right;" name="ask" id="ask" value="ask_query" onclick="show_hideForm()">
                          <i class="mdi mdi-plus"></i>
                        </button>
                               
            
                      <table class="table  table-bordered" id="printTable">
                        <thead>
                          <tr>
          <th>#</th>
          <th style="width:111px">Date</th>
          <th>Topic</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Status</th>
        </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                 @forelse($faqs as $faq)
                 @php  $j++  @endphp
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
          <td >{{$faq->queryDate}}</td>
          <td>{{$faq->topic}}</td>
          <td>{{$faq->subject}}</td>
          <td>{{$faq->message}}</td>
          <td>
          @if(trim($faq->status)=='open')
         <div class="badge badge-primary badge-pill">
          @endif
           @if(trim($faq->status)=='hold')
         <div class="badge badge-warning badge-pill">
          @endif
         @if(trim($faq->status)=='cancel')
         <div class="badge badge-danger badge-pill">
          @endif
          @if(trim($faq->status)=='resolved')
          <div class="badge badge-success badge-pill">
             @endif
           {{ ucfirst(strtolower($faq->status))}}</div></td>
        </tr>
         @empty
         <t>
    
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
<script type="text/javascript">



  function show_hideForm()
  {
    
    $("#askfaq").slideToggle(2000);
   $("#ask").hide();

  }
  
function cancle_faq()
{
$("#askfaq").slideToggle(2000);
 $("#ask").show();  
}
</script>