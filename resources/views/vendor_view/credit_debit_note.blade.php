@extends('layouts.suplier_master')

@section('content')
 
        <div class="page-header">
            <h3 class="page-title">
          Credit/Debit Note
             
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('vendor.home')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Credit/Debit Note</li>
              </ol>
            </nav>

          </div>
 
         <div class="col-md-12 grid-margin stretch-card">
              <div class="card">

                <div class="card-body">
                  <h4 class="card-title">Credit/Debit Note</h4>
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
                  <form  action="{{route('vendor.show_Credit_Debit_Data')}}" method="post">
                    @csrf
             
                     <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Company Code</label>
                      <div class="col-sm-3">
                        <select class="form-control" id="c_code" name="c_code"  onchange="get_plant(this.value)">
                         <option value="">-Select Company Code--</option>
                      @foreach($companies as $company)
                    <option value="{{$company->CompanyCode}}" @if(old('c_code') == $company->CompanyCode) selected="selected" @endif @if(Session::get('ccode')==$company->CompanyCode) selected="selected" @endif> {{  $company->CompanyName.'('.$company->CompanyCode.')' }}</option>
                    @endforeach
                        </select>
                      </div>
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Plant Code</label>
                      <div class="col-sm-3">
                         <select class="form-control" id="p_code" name="p_code">
                          <option value="">--Select Plant Code--</option>
                             @if(Session::get('pcode'))
                             <option value="{{Session::get('pcode')}}" selected="selected">{{Session::get('pcode')}}</option>
                              @endif
                        </select>
                      </div>
                    </div>
               
                         <div class="form-group row">
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">Start Date</label>
                            <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control" name="start_date" id="datepicker" value="{{ old('start_date') }}@if($stdt=Session::get('stdt')) {{ date('m/d/Y',strtotime($stdt)) }} @endif">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                 
                      <label for="vendorCodelable" class="col-sm-3 col-form-label">End Date</label>
                    
                          <div id="datepicker-popup" class="col-sm-3 input-group date datepicker">
                        <input type="text" class="form-control" name="end_date" id="datepicker2" value="{{ old('end_date') }} @if($enddt=Session::get('enddt')) {{ date('m/d/Y',strtotime($enddt)) }} @endif">
                        <span class="input-group-addon input-group-append border-left">
                          <span class="mdi mdi-calendar input-group-text"></span>
                        </span>
                      </div>
                    
                    </div>
                
                    <button type="submit" class="btn btn-gradient-primary mr-2">Get Credit/Debit Note</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
            @endsection

            <script type="text/javascript">


</script>