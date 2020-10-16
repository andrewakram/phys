  <!-- Navigation Bar-->
  <header id="topnav">
            <div class="topbar-main">
                <div class="container-fluid">

                    <!-- Logo container-->
                    <div class="logo">
                        <a href="index" class="logo">
                            <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="30">
                        </a>

                    </div>
                    <!-- End Logo container-->

                    <div class="menu-extras topbar-custom">

                        <!-- Search input -->
                        <div class="search-wrap" id="search-wrap">
                            <div class="search-bar">
                                <input class="search-input" type="search" placeholder="Search" />
                                <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </div>
                        </div>

                        <ul class="list-inline float-right mb-0">
                            <!-- Search -->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">
                                    <i class="mdi mdi-magnify noti-icon"></i>
                                </a>
                            </li>
                            <!-- Fullscreen -->
                            <li class="list-inline-item dropdown notification-list hide-phone">
                                <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                                    <i class="mdi mdi-fullscreen noti-icon"></i>
                                </a>
                            </li>
                            <!-- language-->
                            <li class="list-inline-item dropdown notification-list hide-phone">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    English <img src="{{ URL::asset('assets/images/flags/us_flag.jpg') }}" class="ml-2" height="16" alt=""/>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right language-switch">
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('assets/images/flags/germany_flag.jpg') }}" alt="" height="16"/><span> German </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('assets/images/flags/italy_flag.jpg') }}" alt="" height="16"/><span> Italian </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('assets/images/flags/french_flag.jpg') }}" alt="" height="16"/><span> French </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('assets/images/flags/spain_flag.jpg') }}" alt="" height="16"/><span> Spanish </span></a>
                                    <a class="dropdown-item" href="#"><img src="{{ URL::asset('assets/images/flags/russia_flag.jpg') }}" alt="" height="16"/><span> Russian </span></a>
                                </div>
                            </li>
                            <!-- notification-->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <i class="ion-ios7-bell noti-icon"></i>
                                    <span class="badge badge-danger noti-icon-badge">3</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5>Notification (3)</h5>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                        <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                                        <p class="notify-details"><b>Your order is placed</b><small class="text-muted">Dummy text of the printing and typesetting industry.</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon bg-warning"><i class="mdi mdi-message"></i></div>
                                        <p class="notify-details"><b>New Message received</b><small class="text-muted">You have 87 unread messages</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon bg-info"><i class="mdi mdi-martini"></i></div>
                                        <p class="notify-details"><b>Your item is shipped</b><small class="text-muted">It is a long established fact that a reader will</small></p>
                                    </a>

                                    <!-- All-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        View All
                                    </a>

                                </div>
                            </li>
                            <!-- User-->
                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="{{ URL::asset('assets/images/users/avatar-1.jpg') }}" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <a class="dropdown-item" href="#"><i class="dripicons-user text-muted"></i> Profile</a>
                                    <a class="dropdown-item" href="#"><i class="dripicons-wallet text-muted"></i> My Wallet</a>
                                    <a class="dropdown-item" href="#"><span class="badge badge-success pull-right m-t-5">5</span><i class="dripicons-gear text-muted"></i> Settings</a>
                                    <a class="dropdown-item" href="#"><i class="dripicons-lock text-muted"></i> Lock screen</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="dripicons-exit text-muted"></i> Logout</a>
                                </div>
                            </li>
                            <li class="menu-item list-inline-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle nav-link">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </li>

                        </ul>
                    </div>
                    <!-- end menu-extras -->

                    <div class="clearfix"></div>

                </div> <!-- end container -->
            </div>
            <!-- end topbar-main -->

            <!-- MENU Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-view-dashboard"></i>Dashboard</a>
                                <ul class="submenu">
                                    <li><a href="index">Dashboard 1</a></li>
                                    <li><a href="dashboard-2">Dashboard 2</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-buffer"></i>UI Kit</a>
                                <ul class="submenu megamenu">
                                    <li>
                                        <ul>
                                            <li><a href="ui-buttons">Buttons</a></li>
                                            <li><a href="ui-colors">Colors</a></li>
                                            <li><a href="ui-cards">Cards</a></li>
                                            <li><a href="ui-tabs-accordions">Tabs &amp; Accordions</a></li>
                                            <li><a href="ui-modals">Modals</a></li>
                                            <li><a href="ui-images">Images</a></li>
                                            <li><a href="ui-alerts">Alerts</a></li>
                                            <li><a href="ui-progressbars">Progress Bars</a></li>
                                            <li><a href="ui-dropdowns">Dropdowns</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            <li><a href="ui-lightbox">Lightbox</a></li>
                                            <li><a href="ui-navs">Navs</a></li>
                                            <li><a href="ui-pagination">Pagination</a></li>
                                            <li><a href="ui-popover-tooltips">Popover & Tooltips</a></li>
                                            <li><a href="ui-badge">Badge</a></li>
                                            <li><a href="ui-carousel">Carousel</a></li>
                                            <li><a href="ui-video">Video</a></li>
                                            <li><a href="ui-typography">Typography</a></li>
                                            <li><a href="ui-sweet-alert">Sweet-Alert</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            <li><a href="ui-grid">Grid</a></li>
                                            <li><a href="ui-animation">Animation</a></li>
                                            <li><a href="ui-highlight">Highlight</a></li>
                                            <li><a href="ui-rating">Rating</a></li>
                                            <li><a href="ui-nestable">Nestable</a></li>
                                            <li><a href="ui-alertify">Alertify</a></li>
                                            <li><a href="ui-rangeslider">Range Slider</a></li>
                                            <li><a href="ui-sessiontimeout">Session Timeout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-cube-outline"></i>Components</a>
                                <ul class="submenu">
                                    <li class="has-submenu">
                                        <a href="#">Email</a>
                                        <ul class="submenu">
                                            <li><a href="email-inbox">Inbox</a></li>
                                            <li><a href="email-read">Email Read</a></li>
                                            <li><a href="email-compose">Email Compose</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="widgets">Widgets</a>
                                    </li>
                                    <li>
                                        <a href="calendar">Calendar</a>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Forms</a>
                                        <ul class="submenu">
                                            <li><a href="form-elements">Form Elements</a></li>
                                            <li><a href="form-validation">Form Validation</a></li>
                                            <li><a href="form-advanced">Form Advanced</a></li>
                                            <li><a href="form-wizard">Form Wizard</a></li>
                                            <li><a href="form-editors">Form Editors</a></li>
                                            <li><a href="form-uploads">Form File Upload</a></li>
                                            <li><a href="form-mask">Form Mask</a></li>
                                            <li><a href="form-summernote">Summernote</a></li>
                                            <li><a href="form-xeditable">Form Xeditable</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Charts</a>
                                        <ul class="submenu">
                                            <li><a href="charts-morris">Morris Chart</a></li>
                                            <li><a href="charts-chartist">Chartist Chart</a></li>
                                            <li><a href="charts-chartjs">Chartjs Chart</a></li>
                                            <li><a href="charts-flot">Flot Chart</a></li>
                                            <li><a href="charts-c3">C3 Chart</a></li>
                                            <li><a href="charts-sparkline">Sparkline Chart</a></li>
                                            <li><a href="charts-other">Jquery Knob Chart</a></li>
                                            <li><a href="charts-peity">Peity Chart</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Tables</a>
                                        <ul class="submenu">
                                            <li><a href="tables-basic">Basic Tables</a></li>
                                            <li><a href="tables-datatable">Data Table</a></li>
                                            <li><a href="tables-responsive">Responsive Table</a></li>
                                            <li><a href="tables-editable">Editable Table</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Icons</a>
                                        <ul class="submenu">
                                            <li><a href="icons-material">Material Design</a></li>
                                            <li><a href="icons-ion">Ion Icons</a></li>
                                            <li><a href="icons-fontawesome">Font Awesome</a></li>
                                            <li><a href="icons-themify">Themify Icons</a></li>
                                            <li><a href="icons-dripicons">Dripicons</a></li>
                                            <li><a href="icons-typicons">Typicons Icons</a></li>
                                            <li><a href="icons-weather">Weather Icons</a></li>
                                            <li><a href="icons-mobirise">Mobirise Icons</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Maps</a>
                                        <ul class="submenu">
                                            <li><a href="maps-google"> Google Map</a></li>
                                            <li><a href="maps-vector"> Vector Map</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <a href="#">Email Templates</a>
                                        <ul class="submenu">
                                            <li><a href="email-templates-basic">Basic Action Email</a></li>
                                            <li><a href="email-templates-alert">Alert Email</a></li>
                                            <li><a href="email-templates-billing">Billing Email</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-google-pages"></i>Pages</a>
                                <ul class="submenu megamenu">
                                    <li>
                                        <ul>
                                            <li><a href="pages-login">Login</a></li>
                                            <li><a href="pages-register">Register</a></li>
                                            <li><a href="pages-recoverpw">Recover Password</a></li>
                                            <li><a href="pages-lock-screen">Lock Screen</a></li>
                                            <li><a href="pages-login-2">Login 2</a></li>
                                            <li><a href="pages-register-2">Register 2</a></li>
                                            <li><a href="pages-recoverpw-2">Recover Password 2</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            <li><a href="pages-lock-screen-2">Lock Screen 2</a></li>
                                            <li><a href="pages-timeline">Timeline</a></li>
                                            <li><a href="pages-invoice">Invoice</a></li>
                                            <li><a href="pages-directory">Directory</a></li>
                                            <li><a href="pages-blank">Blank Page</a></li>
                                            <li><a href="pages-404">Error 404</a></li>
                                            <li><a href="pages-500">Error 500</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            <li><a href="pages-pricing">Pricing</a></li>
                                            <li><a href="pages-gallery">Gallery</a></li>
                                            <li><a href="pages-maintenance">Maintenance</a></li>
                                            <li><a href="pages-coming-soon">Coming Soon</a></li>
                                            <li><a href="pages-faq">FAQ</a></li>
                                            <li><a href="pages-contact">Contact</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-cart-outline"></i>Ecommerce</a>
                                <ul class="submenu">
                                    <li><a href="ecommerce-product-list">Product List</a></li>
                                    <li><a href="ecommerce-product-grid">Product Grid</a></li>
                                    <li><a href="ecommerce-order-history">Order History</a></li>
                                    <li><a href="ecommerce-customers">Customers</a></li>
                                    <li><a href="ecommerce-product-edit">Product Edit</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="#" target="_blank"><i class="mdi mdi-airplane"></i>Front End</a>
                            </li>
                            <li>
                                <a href="{{route('users')}}" target="_blank"><i class="mdi mdi-airplane"></i>Users</a>
                            </li>

                        </ul>
                        <!-- End navigation menu -->
                    </div> <!-- end #navigation -->
                </div> <!-- end container -->
            </div> <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->
