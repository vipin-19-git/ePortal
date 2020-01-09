@extends('layouts.admin_login_master')

@section('content')
 
 <div class="wrap-login100" style="background: -webkit-linear-gradient(top, #be1ee98f, #000000">
        
          <span class="login100-form-logo">
           <!--  <i class="zmdi zmdi-landscape"></i> -->
           <img src="{{url('assets/images/groz_logo.gif')}}" alt="logo"
         />
          </span>

          <span class="login100-form-title p-b-34 p-t-27">
          Forgot Password
          </span>
      
          @if ($errors->any())
             <span  style="text-align: center;color: red;" id="alert_msg">
             
           <center><strong>{{ $errors->first() }}</strong></center>
          </span>
          @endif
          @if ($message = Session::get('success'))
              <span  style="text-align: center;color: green;" id="alert_msg">
             <center><strong> {{ $message }}</strong></center>
          </span> 
          @endif
               @if ($message = Session::get('error'))
              <span  style="text-align: center;color: red;" id="alert_msg">
             <center><strong> {{ $message }}</strong></center>
          </span> 
          @endif
          @if ($VenderCode = Session::get('VenderCode'))
       <form class="login100-form validate-form" method="post" action="{{ route('sendResetLink') }}">
           @csrf
       
           <div class="wrap-input100 validate-input" data-validate = "Enter Password">
            <input class="input100" type="password" name="new_password" placeholder="Password">
            <span class="focus-input100" data-placeholder="&#xf207;"></span>
          </div>
           <div class="wrap-input100 validate-input" data-validate = "Enter confirm password">
            <input class="input100" type="password" name="confirm_password" placeholder="Confirm Password">
            <span class="focus-input100" data-placeholder="&#xf207;"></span>
          </div>
     
          <input type="hidden" name="VenderCode" value="{{$VenderCode}}">
          <input type="hidden" name="is_update_Process" value="1">

          <div class="container-login100-form-btn" >
            <button class="login100-form-btn" type="submit" style="background: -webkit-linear-gradient(bottom,#be1ee98f, #000000);">
              Update
            </button>
          </div>
        </form>

          @else
          <form class="login100-form validate-form" method="post" action="{{ route('sendResetLink') }}">
           @csrf
          <div class="wrap-input100 validate-input" data-validate = "Enter username">
            <input class="input100" type="text" name="UserName" placeholder="Username">
            <span class="focus-input100" data-placeholder="&#xf207;"></span>
          </div>
          <input type="hidden" name="is_update_Process" value="2">
       

          <div class="container-login100-form-btn" >
            <button class="login100-form-btn" type="submit" style="background: -webkit-linear-gradient(bottom,#be1ee98f, #000000);">
              Next
            </button>
          </div>
        </form>
          @endif
          @if ($is_admin=Session::get('is_admin')) 
            <div class="text-center p-t-90">
            <a class="txt1" href="@if($is_admin==1){{route('admin_login')}}@else{{route('login')}}@endif">
              Login
            </a>
          </div>
          @else 
         
          <div class="text-center p-t-90">
            <a class="txt1" href="@if(url()->previous()=='http://192.168.1.64:8000/login'){{route('login')}}@else{{route('admin_login')}}@endif">
              Login
            </a>
          </div>
         

          @endif
       

           
      </div>
          
        
       
@endsection
