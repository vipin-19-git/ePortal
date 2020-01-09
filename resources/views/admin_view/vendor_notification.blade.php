@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
             Add New Notification
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Notification</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card" style="@if (! empty($notifications) )display: none @endif" id="main_form">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Add New Notification</h4>
              
                 <form action="{{ route('admin.add_notification') }}" method="post" enctype="multipart/form-data" >
                    @csrf
             
                     <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Notification</label>
                      <div class="col-sm-5" >
                        <textarea class="form-control" rows="5"  name="notification"  id="notification" required style="resize: none;"></textarea>
                   
                    <p id="msg_inv">  </p>
                      </div>
                    
                  
                    </div>
                          <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Entry date</label>
                      <div class="col-sm-5">
                    <input type="text" class="form-control" name="entry_date" id="entry_date" value="{{date('Y-m-d')}}" readonly="">
                    <p id="msg_inv">  </p>
                      </div>
                    
                  
                    </div>
                       <div class="form-group row" id="vendorCodeFrm">
                        <div class="col-sm-2">
                        </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Valied Up-to</label>
                   
                          <div id="datepicker-popup" class="col-sm-5 input-group date datepicker">
                        <input type="text" class="form-control"  id="datepicker" name="valied_date"  value="{{ old('valied_date') }}@if($stdt=Session::get('stdt')) {{ date('m/d/Y',strtotime($stdt)) }} @endif">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    
                    <p id="msg_inv">  </p>
                      
                    
                  
                    </div>
            

       
                   <input type="hidden" name="edit_vend_id" value="@if(isset($user_edit->id)){{$user_edit->id}}@else 1 @endif">
                    <button type="submit" class="btn btn-gradient-primary mr-2"> Save  </button>
                    <button class="btn btn-light" type="reset" onclick="cancle_vendor()">Cancel</button>
                  </form>
                </div>
              </div>
            </div>

           <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Notification Details</h4>

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
         
                      <button type="button" class="btn btn-gradient-warning btn-rounded btn-icon" style="float: right;" name="add_vendor" id="add_vendor" value="add_new_vendor" onclick="show_hideForm()">
                          <i class="mdi mdi-plus"></i>
                        </button>
                               
            
                      <table class="table  table-bordered" id="printTable">
                        <thead>
                          
                     <tr>
                      <th>#</th>
                      <th>Notifications</th>
                      <th>Entry date</th>
                     <th>Valied Upto</th>
                     <th>Status</th>
                    </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                 @forelse($notifications as $notes)
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
          <td>{{$notes->notification}}</td>
           <td>{{$notes->entry_date}}</td>
          <td>{{ $notes->valied_upTo}}</td>
         
          <td>
       
         @if(strtotime($notes->valied_upTo)< strtotime(date('Y-m-d')))
         <div class="badge badge-gradient-danger">
          Inactive
          @endif
        @if(strtotime($notes->valied_upTo) > strtotime(date('Y-m-d')))
          <div class="badge  badge-gradient-success">
            Active
             @endif
           </div></td>
        </tr>
         @empty
         <t>
    
            <td style="text-align: center;color: red" colspan="6">No any Query</td>
                 
                </tr>
                @endforelse
                          
                        </tbody>
                      </table>
                     <div style="float: right">
                           {!! $notifications->render() !!}
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



@endsection
<script type="text/javascript">



  function show_hideForm()
  {
    
    $("#main_form").slideToggle(2000);
   $("#add_vendor").hide();

  }
  
function cancle_vendor()
{
/*$("#main_form").slideToggle(2000);
 $("#add_vendor").show();  */
 location.href="{{ route('admin.add_notification_frm')}}";
}
function change_vendor_status(vnd_status,v_code)
{
  if(vnd_status==1)
  {
    vnd_status=0;
  }
  else
  {
    vnd_status=1;
  }
       $.ajax({

           type:'POST',

           url:'{{ route("admin.change_vnd_status") }}',

           data:{

            vnd_status:vnd_status,
            v_code:v_code,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data){
           
             if(data.success)
             {
               if(vnd_status==1)
               {
                  //$("#vndr_stat").removeClass('badge badge-gradient-success');
                  // $("#vndr_stat").addClass('badge badge-gradient-danger');
                   $("#vndr_stat").removeAttr('onclick');
                   $("#vndr_stat").attr('onclick', 'change_vendor_status(1,'+v_code+')');
                  
               }
               else
               {
                  //$("#vndr_stat").removeClass('badge badge-gradient-danger');
                   //$("#vndr_stat").addClass('badge badge-gradient-success');
                   $("#vndr_stat").removeAttr('onclick');
                   $("#vndr_stat").attr('onclick', 'change_vendor_status(0,'+v_code+')');
               }
               $("#vendorCodeFrm").addClass("form-control-danger");
              $("#msg_inv").html('<label id="vendor-code-error" class="error mt-2 text-danger" for="vcode">Vendor Code already exist !</label>')
             }
             else
             {
             
              $("#vendorCodeFrm").removeClass("form-control-danger");
             
              $("#msg_inv").html('');
              
               
              
             }
           }

        });

}
  function is_vendor_Available()
  {
     var v_code = $("input[name=v_code]").val();
       $.ajax({

           type:'POST',

           url:'{{ route("admin.is_available_vndr") }}',

           data:{

            v_code:v_code,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data){
           
             if(data.success)
             {
               
               $("#vendorCodeFrm").addClass("form-control-danger");
              $("#msg_inv").html('<label id="vendor-code-error" class="error mt-2 text-danger" for="vcode">Vendor Code already exist !</label>')
             }
             else
             {
             
              $("#vendorCodeFrm").removeClass("form-control-danger");
             
              $("#msg_inv").html('');
              
               
              
             }
           }

        });

  


  }
</script>