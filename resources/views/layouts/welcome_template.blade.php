<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Groz Tools: The Global Manufacturer of Machine Tools &amp; Accessories</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ url('assets/welcome_assets/images/groz_favicon.ico') }}" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link href="{{ url('assets/welcome_assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Animate CSS -->
    <link href="{{ url('assets/welcome_assets/vendors/animate/animate.css') }}" rel="stylesheet">
    <!-- Icon CSS-->
    <link rel="stylesheet" href="{{ url('assets/welcome_assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- Camera Slider -->
    <link rel="stylesheet" href="{{ url('assets/welcome_assets/vendors/camera-slider/camera.css') }}">
    <!-- Owlcarousel CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('assets/welcome_assets/vendors/owl_carousel/owl.carousel.css') }}" media="all">

    <!--Theme Styles CSS-->
    <link rel="stylesheet" type="text/css" href="{{ url('assets/welcome_assets/css/style.css') }}" media="all" />

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Preloader -->
    <div class="preloader"></div>

    <!-- Top Header_Area -->
    <section class="top_header_area">
        <div class="container">
            <ul class="nav navbar-nav top_nav">
                <li><a href="#"><i class="fa fa-phone"></i>+91 9771818907</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i>vipin.kumar@mawaimail.com</a></li>
                <li><a href="#"><i class="fa fa-clock-o"></i>Mon - Sat 10:00 - 18:00</a></li>
            </ul>
     <!--        <ul class="nav navbar-nav navbar-right social_nav">
                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
            </ul> -->
        </div>
    </section>
    <!-- End Top Header_Area -->

    <!-- Header_Area -->
    <nav class="navbar navbar-default header_aera" id="main_navbar">
        <div class="container">
            <!-- searchForm -->
            <div class="searchForm">
                <form action="#" class="row m0">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="search" name="search" class="form-control" placeholder="Type & Hit Enter">
                        <span class="input-group-addon form_hide"><i class="fa fa-times"></i></span>
                    </div>
                </form>
            </div><!-- End searchForm -->
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="col-md-2 p0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#min_navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt=""></a>
                </div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="col-md-10 p0">
                <div class="collapse navbar-collapse" id="min_navbar">
                    <ul class="nav navbar-nav navbar-right">
                         <li><a href="{{ url('/') }}">Home</a></li>
                        <li class="dropdown submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">New Vendor</a>
                            <ul class="dropdown-menu">
                                <li><a href="index.html">Vendor Registration</a></li>
                                <li><a href="index-2.html">Vendor Login</a></li>
                            </ul>
                        </li>
                 
                   
                        <li><a href="{{ route('login') }}">Existing Vendor</a></li>
                      
                        <li><a href="{{ route('admin_login') }}">Admin Login</a></li>
                       
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </div><!-- /.container -->
    </nav>
    <!-- End Header_Area -->

    <!-- Slider area -->
    <section class="slider_area row m0">
        <div class="slider_inner">
            <div data-thumb="{{ url('assets/welcome_assets/images/slider_1.jpg') }}" data-src="{{ url('assets/welcome_assets/images/slider_1.jpg') }}">
          
            </div>
            <div data-thumb="{{ url('assets/welcome_assets/images/slider-3.jpg') }}" data-src="{{ url('assets/welcome_assets/images/slider-3.jpg') }}">
        
            </div>
        </div>
    </section>

    <!-- Footer Area -->
    <footer class="footer_area">
        <div class="container">
            <div class="footer_row row">
                <div class="col-md-3 col-sm-6 footer_about">
                    <h2>ABOUT OUR COMPANY</h2>
                    <img src="images/footer-logo.png" alt="">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <ul class="socail_icon">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 footer_about quick">
            
                </div>
                <div class="col-md-3 col-sm-6 footer_about">
                
                </div>
                <div class="col-md-3 col-sm-6 footer_about">
                    <h2>CONTACT US</h2>
                    <address>
                        <p>Have questions, comments or just want to say hello:</p>
                        <ul class="my_address">
                            <li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i>info@thethemspro.com</a></li>
                            <li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i>+880 123 456 789</a></li>
                            <li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>Sector # 10, Road # 05, Plot # 31, Uttara, Dhaka 1230 </span></a></li>
                        </ul>
                    </address>
                </div>
            </div>
        </div>
     
         <div class="copyright_area">
            Copyright © {{ date('Y')}} All rights reserved. Designed by <a href="https://www.mawai.com/" target="_blank">Mawai Infotech Ltd.</a>
        </div>
    </footer>
    <!-- End Footer Area -->

    <!-- jQuery JS -->
    <script src="{{ url('assets/welcome_assets/js/jquery-1.12.0.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ url('assets/welcome_assets/js/bootstrap.min.js') }}"></script>
    <!-- Animate JS -->
    <script src="{{ url('assets/welcome_assets/vendors/animate/wow.min.js') }}"></script>
    <!-- Camera Slider -->
    <script src="{{ url('assets/welcome_assets/vendors/camera-slider/jquery.easing.1.3.js') }}"></script>
    <script src="{{ url('assets/welcome_assets/vendors/camera-slider/camera.min.js') }}"></script>
    <!-- Isotope JS -->
    <script src="{{ url('assets/welcome_assets/vendors/isotope/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ url('assets/welcome_assets/vendors/isotope/isotope.pkgd.min.js') }}"></script>
    <!-- Progress JS -->
    <script src="{{ url('assets/welcome_assets/vendors/Counter-Up/jquery.counterup.min.js') }}"></script>
    <script src="{{ url('assets/welcome_assets/vendors/Counter-Up/waypoints.min.js') }}"></script>
    <!-- Owlcarousel JS -->
    <script src="{{ url('assets/welcome_assets/vendors/owl_carousel/owl.carousel.min.js') }}"></script>
    <!-- Stellar JS -->
    <script src="{{ url('assets/welcome_assets/vendors/stellar/jquery.stellar.js') }}"></script>
    <!-- Theme JS -->
    <script src="{{ url('assets/welcome_assets/js/theme.js') }}"></script>
</body>
</html>
