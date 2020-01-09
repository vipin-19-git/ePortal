@extends('layouts.admin_master')

@section('content')
 
           <div class="page-header">
            <h3 class="page-title">
             Upload Vendor
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dash')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Upload Vendor</li>
              </ol>
            </nav>
          </div>
               <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Upload Vendor</h4>
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
                  <form  action="{{ route('admin.vendRegFormat') }}" method="post" enctype="multipart/form-data">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Upload Vendor</label>
                      <div class="col-sm-3">
                        <input type="file" name="import_file" id="import_file">
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label"></label>
                      <div class="col-sm-3">
                         <button type="submit" class="btn btn-gradient-warning mr-2" name="submit" value="get_format">  <i class="mdi mdi-download menu-icon"></i> Download Format</button>
                      </div>
                       <!-- <label for="vendorCodelable" class="col-sm-2 col-form-label ">PO Number</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control mytxt" style="height: 74%" id="po_no"  name="po_no"  placeholder="PO Number" value="@if($po=Session::get('po_no')) {{ $po}} @else {{ old('po_no') }}  @endif">
                      </div> -->

                    </div>

        
              
                    <button type="submit" class="btn btn-gradient-primary mr-2"> <i class="mdi mdi-upload menu-icon"></i>  Upload</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>

            @endsection