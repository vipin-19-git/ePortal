@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
              Dashboard
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                
              <div class="card bg-gradient-danger border-0 text-white p-3">
                <a href="{{ route('admin.show_pending_reg_req')}}" style="text-decoration: none;color:white">
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi mdi-account-plus mdi-48px"></i>
                    <div class="ml-4">
                      <h2 class="mb-2">{{$no_new_req}}</h2>
                      <h4 class="mb-0" style=" white-space: nowrap; font-size: 1.1rem;">Registration Awaited</h4>
                    </div>
                  </div>
                </div>
                 </a>
              </div>
           
            </div>
            <div class="col-md-4 stretch-card grid-margin">
               
              <div class="card bg-gradient-info border-0 text-white p-3">
                <a href="{{route('admin.show_Hold_reg_req')}}" style="text-decoration: none;color:white">
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <i class="mdi  mdi-pause mdi-48px"></i>
                    <div class="ml-4">
                      <h2 class="mb-2">{{$no_hold_req}}</h2>
                      <h4 class="mb-0" style="  white-space: nowrap;"> Hold Registration </h4>
                    </div>
                  </div>
                </div>
                </a>
              </div>
               
            </div>
            <div class="col-md-4 stretch-card grid-margin">
            
              <div class="card bg-gradient-success border-0 text-white p-3">
                  <a href="{{route('admin.show_pending_query')}}" style="text-decoration: none;color:white">
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <i class="mdi mdi-comment-question-outline mdi-48px"></i>
                    <div class="ml-4">
                      <h2 class="mb-2">{{$nofaq}}</h2>
                      <h4 class="mb-0" style=" white-space: nowrap;">Open Queries</h4>
                    </div>
                  </div>
                </div>
                   </a>     
              </div>
      
            </div>
          </div>
         
       
        

@endsection
