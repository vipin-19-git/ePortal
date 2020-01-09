@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
              Email Work Flow
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Email Work Flow</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card" style="@if (! empty($notifications) )display: none @endif" id="main_form">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title"> Email Work Flow</h4>
              
                 <form action="{{ route('admin.sendWorkFlow') }}" method="post" enctype="multipart/form-data" >
                    @csrf
             
                       <div class="form-group row" >
                        <div class="col-sm-1">
                        </div>
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Vendor Code By</label>
                      <div class="col-sm-8" >
                         <select class="form-control" id="shw_method" name="shw_method"  class="form-control" 
                          onchange="show_field(this.value)">
                        <option value="">-Select--</option>
                        <option value="1">-By Manual--</option>
                        <option value="2">-By Paste--</option>
                     </select>
                       </div>
                      </div>

                      <div class="form-group row" style="display: none" id="by_paste">
                        <div class="col-sm-1">
                        </div>
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Vendor Code</label>
                      <div class="col-sm-8" >
                      <textarea class="form-control" rows="5" cols="52"  name="vendor_code"  id="vendor_codes" placeholder=" Vendor code paste here.." onkeypress="return false;"></textarea>
                       </div>
                      </div>

                <div class="form-group row" style="display: none" id="by_manual">
                   <div class="col-sm-1">
                    </div>
                   <label for="vendorCodelable" class="col-sm-2 col-form-label">Vendor Code</label>
                  <div class="col-sm-3" >
                    <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                     @foreach($users as $user)
                     <option value="{{$user->VenderCode}}">{{$user->UserName.'( '.$user->VenderCode.' )'}}</option>
                      @endforeach
                    </select>
                       </div>

                         <div class="col-sm-2">
     <button type="button" id="search_rightAll" class="btn btn-block"><i class="mdi mdi-fast-forward"></i></button>
        <button type="button" id="search_rightSelected" class="btn btn-block"><i class="mdi mdi-chevron-left"></i></button>
        <button type="button" id="search_leftSelected" class="btn btn-block"><i class="mdi mdi-chevron-right"></i></button>
        <button type="button" id="search_leftAll" class="btn btn-block"><i class="mdi mdi-skip-backward"></i></button>
    </div>
    <div class="col-sm-3">
        <select name="vendr[]" id="search_to" class="form-control" size="8" multiple="multiple"></select>
     </div>
          </div>
          

                       <div class="form-group row" >
                        <div class="col-sm-1">
                        </div>
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Subject</label>
                      <div class="col-sm-8" >
                        <input type="text" class="form-control" name="subject"  id="subject" required="">
                       </div>
                      </div>

                       <div class="form-group row" >
                        <div class="col-sm-1">
                        </div>
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Mesage</label>
                      <div class="col-sm-8" >
                         <textarea id="editor1" name="editor1" rows="10" cols="58">
                                           
                    </textarea>
                       </div>
                      </div>

                       <div class="form-group row" >
                        <div class="col-sm-1">
                        </div>
                      <label for="vendorCodelable" class="col-sm-2 col-form-label">Attachment</label>
                      <div class="col-sm-8" >
                        <input type="file" class="form-control pull-right"  id="attachment" name="attachment">
                       </div>
                      </div>
                   
           
                       <div class="form-group row" >
                        <div class="col-sm-5">
                        </div>
                    
                      <div class="col-sm-5" >
                      <center> <button type="submit" class="btn btn-gradient-primary mr-2"> <i class="mdi  mdi-send"></i> Send  </button>
                    <button class="btn btn-light" type="reset" onclick="cancle_vendor()"><i class="mdi mdi-window-close"></i>Cancel</button>  </center>
                       </div>
                      </div>
       
                   <input type="hidden" name="edit_vend_id" value="@if(isset($user_edit->id)){{$user_edit->id}}@else 1 @endif">
                   
                  </form>
                </div>
              </div>
            </div>

            
           <div class="col-12 grid-margin" >
              <div class="card">
                
                <div class="card-body">
                  <h4 class="card-title">Mail Status Details</h4>

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
          <th>Vendor Code</th>
           <th>Email </th>
          <th>Status</th>
          <th>Description</th>
         </tr>
                          @php
                          $cls="";
                           $type="info";
                          $i=1;
                           $j=0;
                          @endphp

                 @forelse($mail_status as $status)
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
          <td>{{$status->vendorCode}}</td>
           <td>{{$status->mail_id}}</td>
          <td>@if($status->status=='S') Success  @else Failure  @endif </td>
          <td>{{ $status->mail_status_descrp}}</td></td>
        </tr>
         @empty
         <t>
    
            <td style="text-align: center;color: red" colspan="6">No any mail </td>
                 
                </tr>
                @endforelse
                          
                        </tbody>
                      </table>
                     <div style="float: right">
                           {!! $mail_status->render() !!}
                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
 <script src="{{ url('assets/multiselectV/multiselect.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
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

function show_field(val)
      {
       if(val==2)
        {
              $("#by_paste").show();
               $("#by_manual").hide();
        }
        else
        {
           $("#by_paste").hide();
           $("#by_manual").show();
        }
    
      }
      $(document).ready(function() {





  $("#vendor_codes").bind("paste", function(e){

    var pastedData = e.originalEvent.clipboardData.getData('text');
    var rows = pastedData.split("\n");
    $("input[name='vendr[]']").remove();
    for (var i = rows.length - 1; i >= 0; i--) {
      var data=rows[i].trim();
      if(data!='')
      {
          $('<input>').attr({
         type: 'hidden',
         id: 'vend_'+i,
          name: 'vendr[]',
         value:data
         }).appendTo('form');
       }
    }
  });


  });
jQuery(document).ready(function($) {
    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        },
        fireSearch: function(value) {
            return value.length > 3;
        }
    });
});
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
 
  });


</script>