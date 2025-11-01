<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dashboard') - SchoolPro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: linear-gradient(180deg, #2c2c2c 10%, #1a1a1a 100%);
            z-index: 100;
            transition: all 0.3s;
            overflow-y: auto;
        }
        
        .sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .sidebar .sidebar-brand:hover {
            color: #fff;
        }
        
        .sidebar .sidebar-brand .sidebar-brand-icon {
            margin-right: 0.25rem;
        }
        
        .sidebar .nav-item {
            position: relative;
        }
        
        .sidebar .nav-item .nav-link {
            text-align: left;
            padding: 1rem;
            width: 14rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            font-weight: 400;
            display: block;
            text-decoration: none;
        }
        
        .sidebar .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar .nav-item .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-item .nav-link i {
            font-size: 0.85rem;
            margin-right: 0.25rem;
        }
        
        .sidebar .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0 1rem;
        }
        
        .sidebar .sidebar-heading {
            font-size: 0.65rem;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            padding: 1.5rem 1rem 0.5rem;
        }
        
        .sidebar .collapse-inner {
            margin: 0 1rem;
            background-color: transparent !important;
        }
        
        .sidebar .collapse-item {
            color: rgba(255, 255, 255, 0.8);
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        .sidebar .collapse-item:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
        }
        
        .sidebar .collapse-header {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            padding: 1rem 1rem 0.5rem;
            margin-bottom: 0;
        }
        
        /* Content wrapper */
        .content-wrapper {
            margin-left: 270px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Navbar */
        .topbar {
            height: 80px;
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0 1.5rem;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .content-wrapper {
                margin-left: 0;
            }
            .sidebar.show {
                width: 250px;
            }
        }
        
        .border-left-primary { border-left: .25rem solid #4e73df !important; }
        .border-left-warning { border-left: .25rem solid #f6c23e !important; }
        .border-left-success { border-left: .25rem solid #1cc88a !important; }
        .border-left-danger { border-left: .25rem solid #e74a3b !important; }
        .border-left-info { border-left: .25rem solid #36b9cc !important; }
        .text-primary { color: #4e73df !important; }
        .text-warning { color: #f6c23e !important; }
        .text-success { color: #1cc88a !important; }
        .text-danger { color: #e74a3b !important; }
        .text-info { color: #36b9cc !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        .text-gray-900 { color: #3a3b45 !important; }
        .shadow { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }
        .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        .font-weight-bold { font-weight: 700 !important; }
        .text-uppercase { text-transform: uppercase !important; }
        .text-xs { font-size: 0.75rem; }
        .h5 { font-size: 1.25rem; }
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: 0.25rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        .py-3 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        .py-4 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
        .mr-2 { margin-right: 0.5rem !important; }
        .h-100 { height: 100% !important; }
        .no-gutters { margin-right: 0; margin-left: 0; }
        .no-gutters > .col { padding-right: 0; padding-left: 0; }
        .align-items-center { align-items: center !important; }
        .dropdown-toggle::after { display: none; }
        .btn-link { color: #5a5c69; text-decoration: none; }
        .btn-link:hover { color: #4e73df; text-decoration: none; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <!-- Sidebar Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard.student') }}">
            <div class="sidebar-brand-text mx-3">SchoolPro</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('dashboard.student') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.student') }}">
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            My Project Journey
        </div>

        <!-- Nav Item - My Project -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProject"
                aria-expanded="true" aria-controls="collapseProject">
                <span>My Project</span>
            </a>
            <div id="collapseProject" class="collapse {{ request()->routeIs('student.projects.*') ? 'show' : '' }}" aria-labelledby="headingProject" data-bs-parent="#accordionSidebar">
                <div class="py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Project Management:</h6>
                    <a class="collapse-item {{ request()->routeIs('student.projects.index') ? 'active' : '' }}" href="{{ route('student.projects.index') }}">My Projects</a>
                    <a class="collapse-item {{ request()->routeIs('student.projects.proposal') ? 'active' : '' }}" href="{{ route('student.projects.proposal') }}">New Proposal</a>
                    <a class="collapse-item" href="/student/project/final">Final Submission</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Weekly Logs -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLogs"
                aria-expanded="true" aria-controls="collapseLogs">
                <span>Weekly Progress Logs</span>
            </a>
            <div id="collapseLogs" class="collapse" aria-labelledby="headingLogs" data-bs-parent="#accordionSidebar">
                <div class="py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Progress Tracking:</h6>
                    <a class="collapse-item" href="/student/logs/upload">Upload New Log</a>
                    <a class="collapse-item" href="/student/logs/history">Log History</a>

                </div>
            </div>
        </li>

        <!-- Nav Item - Profile -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.edit') }}">
                <span>My Profile</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Logout -->
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="nav-link" href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    <span>Logout</span>
                </a>
            </form>
        </li>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Navbar -->
        <nav class="topbar navbar navbar-expand navbar-light bg-white shadow">
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                        <img class="img-profile rounded-circle"
                            src="{{ auth()->user()->profile_picture_url ?? asset('images/default-avatar.png') }}" width="40" height="40">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="py-4">
            @if(session('success'))
                <div class="container-fluid px-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container-fluid px-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="container-fluid px-4">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="container-fluid px-4">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Content Wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script>
        // Toggle the side navigation
        (function($) {
            "use strict"; // Start of use strict
            
            // Toggle the side navigation
            $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
                $("body").toggleClass("sidebar-toggled");
                $(".sidebar").toggleClass("toggled");
                if ($(".sidebar").hasClass("toggled")) {
                    $('.sidebar .collapse').collapse('hide');
                };
            });
            
            // Close any open menu accordions when window is resized below 768px
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    $('.sidebar .collapse').collapse('hide');
                };
                
                // Toggle the side navigation when window is resized below 480px
                if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
                    $("body").addClass("sidebar-toggled");
                    $(".sidebar").addClass("toggled");
                    $('.sidebar .collapse').collapse('hide');
                };
            });
            
            // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
            $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
                if ($(window).width() > 768) {
                    var e0 = e.originalEvent,
                        delta = e0.wheelDelta || -e0.detail;
                    this.scrollTop += (delta < 0 ? 1 : -1) * 30;
                    e.preventDefault();
                }
            });
            
        })(jQuery); // End of use strict
    </script>

    @stack('scripts')
</body>
</html>