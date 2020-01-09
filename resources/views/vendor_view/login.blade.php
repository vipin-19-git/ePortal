@extends('layouts.login_master')

@section('content')
 
 <div class="wrap-login100" >
        <form class="login100-form validate-form" method="post" action="{{ route('login') }}">
           @csrf
          <span class="login100-form-logo">
           <!--  <i class="zmdi zmdi-landscape"></i> -->
           <img src="{{url('assets/images/groz_logo.gif')}}" alt="logo"
         />
          </span>

          <span class="login100-form-title p-b-34 p-t-27" >
            Vendor Log in
          </span>
          @if ($errors->any())
             <span  style="text-align: center;color: red;" id="alert_msg">
             
           <center><strong>{{ $errors->first() }}</strong></center>
          </span>
          @endif
          <div class="wrap-input100 validate-input" data-validate = "Enter username">
            <input class="input100" type="text" name="UserName" placeholder="Username">
            <span class="focus-input100" data-placeholder="&#xf207;"></span>
          </div>

          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <input class="input100" type="password" name="WebPassword" placeholder="Password">
            <span class="focus-input100" data-placeholder="&#xf191;"></span>
          </div>

          <div class="contact100-form-checkbox">
            <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
            <label class="label-checkbox100" for="ckb1">
              Remember me
            </label>
          </div>

          <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
              Login
            </button>
          </div>

          <div class="text-center p-t-90">
            <a class="txt1" href="{{route('forGetPassword')}}">
              Forgot Password?
            </a>
          </div>
        </form>
      </div>
        

@endsection
