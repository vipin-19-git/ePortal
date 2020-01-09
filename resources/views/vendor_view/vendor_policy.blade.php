@extends('layouts.suplier_master')

@section('content')
 
        <div class="page-header">
            <h3 class="page-title">
        Vendor Policy
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vendor Policy</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Vendor Policy</h4>
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
                 
                     <div class="form-group row">
                     
                      <div class="col-sm-12">
                         <iframe src="{{asset($vendor_policy)}}" height="600px" align="center" width="100%" alt="pdf"></iframe>
                      </div>
                      
                    </div>
           

                </div>
              </div>
            </div>
            @endsection

        <script type="text/javascript">
</script>