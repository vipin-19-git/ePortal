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
   

  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{url('assets/css/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{url('assets/images/groz_logo.gif')}}" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{route('admin.dash')}}"><img src="{{url('assets/images/groz_logo.gif')}}" alt="logo"
         style="width: 200px;height: 54px;"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{route('admin.dash')}}"><img src="{{url('assets/images/groz_logo.gif')}}" alt="logo" style="width: 74px;height:55px;" /></a>
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
              <i class="mdi mdi-bell-outline"> </i>
             <span class="count-symbol bg-info"> {{$nofaq}} </span>

            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <h6 class="p-3 mb-0">Notifications</h6>

             @forelse($vendor_faqs as $faq)
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item" href="{{route('admin.show_vendorWise_query',['Vendor' => $faq->vendorCode])}}">
                <span class="badge badge-gradient-warning badge-pill">{{$faq->total}}</span>
                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                  <h6 class="preview-subject font-weight-normal mb-1">{{$faq->UserName}}</h6>
                 
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
              <a class="dropdown-item preview-item" href="{{ route('admin.show_vendor_query') }}"> <h6 class="p-3 mb-0 text-center">See all notifications</h6> </a>
            </div>
          </li>
          
         
          <li class="nav-item nav-logout d-none d-lg-block">
            <a class="nav-link" href="{{ route('admin_logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
              Logout
              <i class="mdi mdi-power"></i>
            </a>

              <form id="logout-form" action="{{ route('admin_logout') }}" method="POST" style="display: none;">
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
            <a class="nav-link" href="{{route('admin.dash')}}">
              <i class="mdi mdi-home-outline menu-icon"></i>              
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('admin.delivery_schedule')}}" aria-expanded="false" aria-controls="page-layouts">
              <i class="mdi mdi-calendar menu-icon"></i>              
              <span class="menu-title">Delivery Schedule</span>
             <!--  <i class="menu-arrow"></i> -->
            </a>
  
          </li>
               <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.show_vendor_query') }}">
              <i class="mdi mdi-backup-restore menu-icon"></i>              
              <span class="menu-title">Notifications</span>
              <span class="badge badge-gradient-success badge-pill">{{$nofaq}}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('admin.upload_vendor')}}" aria-expanded="false" aria-controls="sidebar-layouts">
              <i class="mdi mdi-upload menu-icon"></i>              
              <span class="menu-title">Upload Vendor</span>
             <!--  <i class="menu-arrow"></i> -->
            </a>
    
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('admin.add_vendor')}}" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-account-plus menu-icon"></i>              
              <span class="menu-title">Add Vendor</span>
              <!-- <i class="menu-arrow"></i> -->
            </a>
     
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{route('admin.delete_invoice_frm')}}" aria-expanded="false" aria-controls="ui-advanced">
              <i class="mdi mdi-file-document menu-icon"></i>              
              <span class="menu-title">Delete Invoice</span>
              <!-- <i class="menu-arrow"></i> -->
            </a>
     
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.add_notification_frm')}}">
              <i class="mdi  mdi-bell-plus menu-icon"></i>              
              <span class="menu-title">Add Notification</span>
            
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link"  href="{{ route('admin.add_vendorPolicy_frm')}}" aria-expanded="false" aria-controls="icons">
              <i class="mdi mdi-note-plus menu-icon"></i>              
              <span class="menu-title">Add Vendor Policy</span>
             <!--  <i class="menu-arrow"></i> -->
            </a>
        
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.show_New_Vendor_Request')}}">
              <i class="mdi  mdi-new-box menu-icon"></i>              
              <span class="menu-title">New Registration Request</span>
              <!-- <span class="badge badge-gradient-success badge-pill">9</span> -->
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.getWorkFlow')}}">
              <i class="mdi mdi-email menu-icon"></i>              
              <span class="menu-title">Email Work Flow</span>              
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link"  href="{{route('admin.get_Change_Pass_Frm')}}" aria-expanded="false" aria-controls="forms">
              <i class="mdi mdi-key-change menu-icon"></i>              
              <span class="menu-title">Change Password</span>
            <!--   <i class="menu-arrow"></i> -->
            </a>
    
          </li>
        


          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts" href="{{ route('admin_logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >
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
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
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
   <script src="{{url('assets/js/form-validation.js')}}"></script>
  <script src="{{url('assets/js/bt-maxLength.js')}}"></script>

    <script src="{{url('assets/js/tabs.js')}}"></script>
    <script src="{{url('assets/js/modal-demo.js')}}"></script>
    <script src="{{ url('assets/multiselectV/multiselect.min.js')}}"></script>
  <!-- End custom js for this page-->
</body>

</html>
<script type="text/javascript">
   $('.datepicker').each(function(){
    $(this).datepicker();
});
   $(function() {
    $("#alert_msg").delay(5000).fadeOut('slow');
   
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
</script>