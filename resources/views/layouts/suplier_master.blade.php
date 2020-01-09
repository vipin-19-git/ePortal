<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ePortal</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{url('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{url('assets/vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{url('assets/vendors/css/vendor.bundle.addons.css')}}">
   <link rel="stylesheet" href="{{url('assets/vendors/iconfonts/font-awesome/css/font-awesome.min.css')}}" />
   <link rel="stylesheet" href="{{url('assets/vendors/iconfonts/font-awesome/css/font-awesome.min.css')}}">
<!--     <link rel="stylesheet" href="{{url('assets/vendors/icheck/skins/all.css')}}"> -->
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{url('assets/css/style.css')}}">
  <!-- endinject -->
  <style>
  
</style>
  <link rel="shortcut icon" href="{{url('assets/images/groz_logo.gif')}}" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{route('vendor.home')}}"><img src="{{url('assets/images/groz_logo.gif')}}" alt="logo"
         style="width: 200px;height: 54px;"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{route('vendor.home')}}"><img src="{{url('assets/images/groz_logo.gif')}}" alt="logo" style="width: 74px;height:55px;" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <span class="d-none d-md-inline">Welcome {{ Auth::user()->UserName}}</span>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile">
            <a class="nav-link">
              <div class="nav-profile-text">
                <p class="mb-0">{{ Auth::user()->VenderCode}}</p>
              </div>
              <div class="nav-profile-img">
                <img src="{{url('assets/images/default.png')}}" alt="image">
              </div>
            </a>
          </li>
 
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell-outline"></i>
              <span class="count-symbol bg-info">{{$no_of_notification}}<!-- <div class="badge badge-pill badge-primary"></div> --></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown" style="overflow:scroll; height:310px;">
              <h6 class="p-3 mb-0">Notifications   </h6>
               @php $nc=0; @endphp 
              @forelse($notifications as $nots)
              @php $nc++;  @endphp 
             
              <div class="dropdown-divider  @if($nc>3) unhide  @endif"   @if($nc>3)  style="display:none !important;" @endif  ></div>
              <a class="dropdown-item preview-item @if($nc>3) unhide  @endif" @if($nc>3)  style="display:none !important;" @endif>
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="mdi  mdi-bullhorn"></i>
                  </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                 <!--  <h6 class="preview-subject font-weight-normal mb-1">Event today</h6> -->
                  <p class="text-gray ellipsis mb-0">
                   {{$nots->notification}}
                  </p>
                </div>
              </a>

               @empty
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="mdi mdi-calendar"></i>
                  </div>
                </div>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1"> No any notification</h6>
                  
                </div>
              </a>


               @endforelse

              <div class="dropdown-divider"></div>
                 <a class="dropdown-item" onclick="see_all_notification();" >
              <h6 class="p-3 mb-0 text-center" >See all notifications</h6>
                </a>
               
         
            </div>
          </li>
          
         
          <li class="nav-item nav-logout d-none d-lg-block">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
              Logout
              <i class="mdi mdi-power"></i>
            </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div id="settings-trigger"><i class="mdi mdi-format-color-fill"></i></div>
      <div id="theme-settings" class="settings-panel">
        <i class="settings-close mdi mdi-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-default-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Default</div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles primary"></div>
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <span class="nav-link">
              <img src="{{url('assets/images/default.png')}}" alt="image">
              <span class="nav-profile-text">{{ Auth::user()->VenderCode}}</span>
             <!--  <span class="badge badge-pill badge-gradient-danger">1</span> -->
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('vendor.home')}}">
              <i class="mdi mdi-home-outline menu-icon"></i>              
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('vendor.delivery_schedule')}}" aria-expanded="false" aria-controls="page-layouts">
              <i class="mdi mdi-calendar menu-icon"></i>              
              <span class="menu-title">Delivery Schedules</span>
             <!--  <i class="menu-arrow"></i> -->
            </a>
  
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('vendor.reprint')}}" aria-expanded="false" aria-controls="sidebar-layouts">
              <i class="mdi mdi-cloud-print menu-icon"></i>              
              <span class="menu-title">Print/Reprint Barcode</span>
             <!--  <i class="menu-arrow"></i> -->
            </a>
    
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('vendor.get_recon_frm')}}" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-grease-pencil menu-icon"></i>              
              <span class="menu-title">Reconcilation Statement</span>
              <!-- <i class="menu-arrow"></i> -->
            </a>
     
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('vendor.get_Invoice_Status_Frm')}}" aria-expanded="false" aria-controls="ui-advanced">
              <i class="mdi mdi-file-document menu-icon"></i>              
              <span class="menu-title">Invoice Clearance Status</span>
              <!-- <i class="menu-arrow"></i> -->
            </a>
     
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('vendor.get_Material_Return_Frm')}}">
              <i class="mdi  mdi-keyboard-return menu-icon"></i>              
              <span class="menu-title">Material returned</span>
            
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('vendor.get_Payment_Advice_Frm')}}" aria-expanded="false" aria-controls="icons">
              <i class="mdi mdi-credit-card menu-icon"></i>              
              <span class="menu-title">Payment Advice</span>
             <!--  <i class="menu-arrow"></i> -->
            </a>
        
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('vendor.get_Credit_Debit_Frm') }}">
              <i class="mdi  mdi-currency-inr menu-icon"></i>              
              <span class="menu-title">Credit/Debit Note</span>
              <!-- <span class="badge badge-gradient-success badge-pill">9</span> -->
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('vendor.get_Delivery_Delay_Frm')}}">
              <i class="mdi mdi-information menu-icon"></i>              
              <span class="menu-title">Deliver Delay Information</span>              
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('vendor.Vendor_Policy') }}" aria-expanded="false" aria-controls="forms">
              <i class="mdi mdi mdi-file-outline menu-icon"></i>              
              <span class="menu-title">Vendor Policy</span>
            <!--   <i class="menu-arrow"></i> -->
            </a>
    
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('vendor.get_ask_Query_Frm') }}">
              <i class="mdi mdi-comment-question-outline  menu-icon"></i>              
              <span class="menu-title">Ask Query</span>
            </a>
          </li>
         <!--  <li class="nav-item">
            <a class="nav-link" href="pages/forms/code_editor.html">
              <i class="mdi mdi-quicktime menu-icon"></i>              
              <span class="menu-title">QRQC</span>
            
            </a>
          </li>
 -->

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
              <i class="mdi  mdi-logout menu-icon"></i>              
              <span class="menu-title">Sign out</span>
              <!-- <i class="menu-arrow"></i> -->
            </a>

          </li>
         
      
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          

          @yield('content') 

      </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © {{ date('Y')}} <a href="https://www.mawai.com/" target="_blank">Mawai Infotech Ltd.</a>. All rights reserved.</span>
           
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{url('assets/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{url('assets/vendors/js/vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{url('assets/js/off-canvas.js')}}"></script>
  <script src="{{url('assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{url('assets/js/misc.js')}}"></script>
  <script src="{{url('assets/js/settings.js')}}"></script>
  <script src="{{url('assets/js/todolist.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{url('assets/js/dashboard.js')}}"></script>
    <script src="{{url('assets/js/chart.js')}}"></script>
      <script src="{{url('assets/js/flot-chart.js')}}"></script>
  <!-- End custom js for this page-->
   <script src="{{url('assets/js/form-validation.js')}}"></script>
    <script src="{{url('assets/js/bt-maxLength.js')}}"></script>
      <script src="{{url('assets/js/toastDemo.js')}}"></script>
  <!-- <script src="{{url('assets/js/desktop-notification.js')}}"></script>
 -->
   <script src="{{url('assets/js/formpickers.js')}}"></script>
  <script src="{{url('assets/js/form-addons.js')}}"></script>
  <script src="{{url('assets/js/x-editable.js')}}"></script>
  <script src="{{url('assets/js/dropify.js')}}"></script>
  <script src="{{url('assets/js/dropzone.js')}}"></script>
  <script src=".{{url('assets/js/jquery-file-upload.js')}}"></script>
  <script src=".{{url('assets/js/formpickers.js')}}"></script>
  <script src="{{url('assets/js/form-repeater.js')}}"></script>
    <script src="{{url('assets/js/file-upload.js')}}"></script>
 <!--  <script src="{{url('assets/js/iCheck.js')}}"></script> -->
  <script src="{{url('assets/js/typeahead.js')}}"></script>
  <script src="{{url('assets/js/select2.js')}}"></script>
</body>

</html>
<script type="text/javascript">
   $('.datepicker').each(function(){
    $(this).datepicker();
});
   $(function() {
    $("#alert_msg").delay(5000).fadeOut('slow');
    // setTimeout() function will be fired after page is loaded
    // it will wait for 5 sec. and then will fire
    // $("#successMessage").hide() function
    /*setTimeout(function() {
        $("#alert_msg").hide();
    }, 5000)*/;
});
  //$('#datepicker2').datepicker();
    function get_plant(Comp_code)
  {
    
       $.ajax({

           type:'POST',

           url:'{{ route("vendor.get_corresponding_plant") }}',

           data:{

            Comp_code:Comp_code,
           "_token": "{{ csrf_token() }}"
           },

           success:function(data)
           {
              var default_plant='<option value="">--Select Plant Code--</option>';
              var plant_opt="";
       
             if(data.count>0)
             {
              for(var i=0;i<data.count;i++)
              {
                plant_opt+='<option value="'+data.plants[i].PlantCode+'">'+data.plants[i].PlantName+'('+data.plants[i].PlantCode+')'+'</option>';
                 
                
              }
             }
             $("#p_code").html(default_plant+plant_opt);
           }

        });
  }

 function see_all_notification()
 {
   $('.unhide').css('display','block');


 } 

</script>