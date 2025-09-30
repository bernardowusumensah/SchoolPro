<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        .sidebar .sidebar-brand .sidebar-brand-text {
            margin-left: 0.25rem;
        }
        
        .sidebar .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            position: relative;
            transition: all 0.15s ease-in-out;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            font-size: 0.85rem;
            margin-right: 0.25rem;
            min-width: 2rem;
            text-align: center;
        }
        
        .sidebar .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0 1rem 1rem;
        }
        
        .sidebar .sidebar-heading {
            font-size: 0.75rem;
            font-weight: 800;
            padding: 0.25rem 1rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }
        
        /* Content wrapper */
        .content-wrapper {
            margin-left: 250px;
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
        .text-primary { color: #4e73df !important; }
        .text-warning { color: #f6c23e !important; }
        .text-success { color: #1cc88a !important; }
        .text-danger { color: #e74a3b !important; }
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
        
        /* Icon Circle Styles */
        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-warning { background-color: #f6c23e !important; }
        .bg-info { background-color: #36b9cc !important; }
        .bg-success { background-color: #1cc88a !important; }
        .bg-danger { background-color: #e74a3b !important; }
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
</head>
<body>
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <!-- Sidebar Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard/admin">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SchoolPro</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="/dashboard/admin">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            User Management
        </div>

        <!-- Nav Item - User Accounts -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUsers"
                aria-expanded="true" aria-controls="collapseUsers">
                <i class="fas fa-fw fa-users"></i>
                <span>User Accounts</span>
            </a>
            <div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Account Management:</h6>
                    <a class="collapse-item" href="/admin/users">All Users</a>
                    <a class="collapse-item" href="/admin/users/create">Create Account</a>
                    <a class="collapse-item" href="/admin/users/students">Manage Students</a>
                    <a class="collapse-item" href="/admin/users/teachers">Manage Teachers</a>
                    <a class="collapse-item" href="/admin/users/inactive">Deactivated Users</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Project Oversight -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProjects"
                aria-expanded="true" aria-controls="collapseProjects">
                <i class="fas fa-fw fa-project-diagram"></i>
                <span>Project Oversight</span>
            </a>
            <div id="collapseProjects" class="collapse" aria-labelledby="headingProjects" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Platform Projects:</h6>
                    <a class="collapse-item" href="/admin/projects">All Projects</a>
                    <a class="collapse-item" href="/admin/projects/active">Active Projects</a>
                    <a class="collapse-item" href="/admin/projects/completed">Completed Projects</a>
                    <a class="collapse-item" href="/admin/projects/pending">Pending Approval</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            System Monitoring
        </div>

        <!-- Nav Item - Reports -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports"
                aria-expanded="true" aria-controls="collapseReports">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">System Reports:</h6>
                    <a class="collapse-item" href="/admin/reports/users">Active Users Report</a>
                    <a class="collapse-item" href="/admin/reports/projects">Projects Report</a>
                    <a class="collapse-item" href="/admin/reports/approvals">Pending Approvals</a>
                    <a class="collapse-item" href="/admin/reports/performance">System Performance</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Communications -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseComms"
                aria-expanded="true" aria-controls="collapseComms">
                <i class="fas fa-fw fa-bullhorn"></i>
                <span>Communications</span>
            </a>
            <div id="collapseComms" class="collapse" aria-labelledby="headingComms" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Announcements:</h6>
                    <a class="collapse-item" href="/admin/announcements">All Announcements</a>
                    <a class="collapse-item" href="/admin/announcements/create">Send Announcement</a>
                    <a class="collapse-item" href="/admin/notifications">System Notifications</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Analytics & Insights
        </div>

        <!-- Nav Item - Analytics Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="/admin/analytics">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Analytics Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Heading -->
        <div class="sidebar-heading">
            System Settings
        </div>

        <!-- Nav Item - Settings -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSettings"
                aria-expanded="true" aria-controls="collapseSettings">
                <i class="fas fa-fw fa-cog"></i>
                <span>Settings</span>
            </a>
            <div id="collapseSettings" class="collapse" aria-labelledby="headingSettings" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">System Config:</h6>
                    <a class="collapse-item" href="/admin/settings/general">General Settings</a>
                    <a class="collapse-item" href="/admin/settings/email">Email Configuration</a>
                    <a class="collapse-item" href="/admin/settings/backup">System Backup</a>
                    <a class="collapse-item" href="/admin/logs">Activity Logs</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Logout -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search users, projects, reports..."
                        aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                </li>

                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                </li>

                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope fa-fw"></i>
                        <!-- Counter - Messages -->
                        <span class="badge badge-danger badge-counter">7</span>
                    </a>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Admin User' }}</span>
                        <img class="img-profile rounded-circle"
                            src="{{ auth()->user()->profile_picture_url }}" 
                            alt="Profile Picture"
                            style="width: 60px; height: 60px; object-fit: cover;">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/profile">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="/admin/settings">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="/activity">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
                <a href="/admin/reports" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                </a>
            </div>

            <!-- Hidden logout form for dropdown -->
            <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <!-- Content Row -->
            <div class="row">

            <!-- Active Users Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Active Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_users'] ?? '47' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Activity Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Active Projects</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_projects'] ?? '23' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pending Approvals</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $stats['pending_approvals'] ?? '8' }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Alerts Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    System Alerts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['system_alerts'] ?? '2' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- User Activity Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">User Activity Overview</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Chart Options:</div>
                                <a class="dropdown-item" href="/admin/reports/users">Detailed User Report</a>
                                <a class="dropdown-item" href="/admin/analytics">Full Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/admin/reports/export">Export Data</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Status Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Project Status Distribution</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink2">
                                <div class="dropdown-header">Project Options:</div>
                                <a class="dropdown-item" href="/admin/projects">View All Projects</a>
                                <a class="dropdown-item" href="/admin/projects/pending">Pending Approvals</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/admin/reports/projects">Projects Report</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="myBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-4">

                <!-- Project Oversight -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Project Oversight</h6>
                        <a href="/admin/projects" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">Pending Approvals <span class="float-right">3 projects</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <h4 class="small font-weight-bold">In Progress <span class="float-right">12 projects</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <h4 class="small font-weight-bold">Review Required <span class="float-right">5 projects</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <h4 class="small font-weight-bold">Completed This Week <span class="float-right">8 projects</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="text-center">
                            <a href="/admin/projects/pending" class="btn btn-warning btn-sm mr-2">
                                <i class="fas fa-clock"></i> Review Pending
                            </a>
                            <a href="/admin/projects/overdue" class="btn btn-danger btn-sm">
                                <i class="fas fa-exclamation-triangle"></i> Overdue Items
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 mb-4">

                <!-- Admin Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Admin Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <a href="/admin/users/create" class="btn btn-primary btn-block shadow-sm">
                                    <i class="fas fa-user-plus"></i><br>
                                    <small>Create User Account</small>
                                </a>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <a href="/admin/users" class="btn btn-info btn-block shadow-sm">
                                    <i class="fas fa-users-cog"></i><br>
                                    <small>Manage All Users</small>
                                </a>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <a href="/admin/projects" class="btn btn-success btn-block shadow-sm">
                                    <i class="fas fa-project-diagram"></i><br>
                                    <small>View All Projects</small>
                                </a>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <a href="/admin/reports" class="btn btn-warning btn-block shadow-sm">
                                    <i class="fas fa-chart-bar"></i><br>
                                    <small>Generate Reports</small>
                                </a>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <a href="/admin/announcements/create" class="btn btn-danger btn-block shadow-sm">
                                    <i class="fas fa-bullhorn"></i><br>
                                    <small>Send Announcement</small>
                                </a>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <a href="/admin/analytics" class="btn btn-secondary btn-block shadow-sm">
                                    <i class="fas fa-chart-line"></i><br>
                                    <small>Analytics Dashboard</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Recent User Activity -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent User Activity</h6>
                <a href="/admin/users" class="btn btn-primary btn-sm">Manage All Users</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Account Status</th>
                                <th>Registered</th>
                                <th>Admin Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($recentUsers) && count($recentUsers) > 0)
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2" src="{{ $user->profile_picture_url }}" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($user->role ?? 'Student') }}
                                        </span>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-success">Active</span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="/admin/users/{{ $user->id }}" class="btn btn-sm btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-sm btn-warning" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->status == 'active')
                                            <form method="POST" action="/admin/users/{{ $user->id }}/deactivate" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Deactivate User" onclick="return confirm('Deactivate this user?')">
                                                    <i class="fas fa-user-slash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="/admin/users/{{ $user->id }}/activate" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" title="Activate User">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2" src="/images/default-avatar.png" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                            John Smith
                                        </div>
                                    </td>
                                    <td><span class="badge badge-primary">Student</span></td>
                                    <td>john.smith@student.edu</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>{{ now()->subHours(2)->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary" title="View Details"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning" title="Edit User"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger" title="Deactivate User"><i class="fas fa-user-slash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2" src="/images/default-avatar.png" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                            Sarah Johnson
                                        </div>
                                    </td>
                                    <td><span class="badge badge-warning">Teacher</span></td>
                                    <td>sarah.johnson@school.edu</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>{{ now()->subHours(5)->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary" title="View Details"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning" title="Edit User"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger" title="Deactivate User"><i class="fas fa-user-slash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2" src="/images/default-avatar.png" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                            Mike Davis
                                        </div>
                                    </td>
                                    <td><span class="badge badge-primary">Student</span></td>
                                    <td>mike.davis@student.edu</td>
                                    <td><span class="badge badge-warning">Inactive</span></td>
                                    <td>{{ now()->subDay()->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary" title="View Details"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning" title="Edit User"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-success" title="Activate User"><i class="fas fa-user-check"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle mr-2" src="/images/default-avatar.png" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                            Lisa Wilson
                                        </div>
                                    </td>
                                    <td><span class="badge badge-warning">Teacher</span></td>
                                    <td>lisa.wilson@school.edu</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>{{ now()->subDays(2)->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary" title="View Details"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-warning" title="Edit User"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger" title="Deactivate User"><i class="fas fa-user-slash"></i></button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Projects and System Notifications Row -->
        <div class="row">

            <!-- Recent Project Submissions -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Project Submissions</h6>
                        <a href="/admin/projects" class="btn btn-primary btn-sm">View All Projects</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="border-left-warning">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-warning">
                                                        <i class="fas fa-file-alt text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">Mobile App UI Design</div>
                                                    <div class="text-gray-500 small">Submitted by John Smith • 2 hours ago</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-sm btn-warning">Review</a>
                                            <a href="#" class="btn btn-sm btn-success">Approve</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-left-info">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-info">
                                                        <i class="fas fa-code text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">E-commerce Platform</div>
                                                    <div class="text-gray-500 small">Submitted by Sarah Johnson • 5 hours ago</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-sm btn-info">In Review</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-left-success">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-success">
                                                        <i class="fas fa-check text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">Database Management System</div>
                                                    <div class="text-gray-500 small">Submitted by Mike Davis • 1 day ago</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <span class="badge badge-success">Approved</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Notifications -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Admin Notifications</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-danger">
                                        <i class="fas fa-user-plus text-white"></i>
                                    </div>
                                </div>
                                <div class="font-weight-bold text-gray-800">
                                    {{ $stats['pending_approvals'] ?? '3' }} new user accounts pending approval
                                    <div class="small text-gray-500">Requires admin action</div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-project-diagram text-white"></i>
                                    </div>
                                </div>
                                <div class="font-weight-bold text-gray-800">
                                    {{ $stats['active_projects'] ?? '12' }} projects currently active
                                    <div class="small text-gray-500">Platform activity status</div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-info">
                                        <i class="fas fa-bullhorn text-white"></i>
                                    </div>
                                </div>
                                <div class="font-weight-bold text-gray-800">
                                    Last announcement sent successfully
                                    <div class="small text-gray-500">To all {{ $stats['active_users'] ?? '47' }} active users</div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-chart-line text-white"></i>
                                    </div>
                                </div>
                                <div class="font-weight-bold text-gray-800">
                                    System performance: Optimal
                                    <div class="small text-gray-500">All monitoring systems operational</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/admin/reports" class="btn btn-primary btn-sm">Generate Reports</a>
                            <a href="/admin/announcements/create" class="btn btn-info btn-sm">Send Announcement</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        </div>
        <!-- /.container-fluid -->
        
    </div>
    <!-- End of Content Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Area Chart Example
            var ctx = document.getElementById("myAreaChart");
            if (ctx) {
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Earnings",
                            lineTension: 0.3,
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000],
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            x: {
                                time: {
                                    unit: 'date'
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 7
                                }
                            },
                            y: {
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    // Include a dollar sign in the ticks
                                    callback: function(value, index, values) {
                                        return '$' + number_format(value);
                                    }
                                },
                                grid: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            },
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyColor: "#858796",
                                titleMarginBottom: 10,
                                titleColor: '#6e707e',
                                titleFont: {
                                    size: 14
                                },
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                        return datasetLabel + ': $' + number_format(tooltipItem.parsed.y);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Bar Chart Example
            var ctx2 = document.getElementById("myBarChart");
            if (ctx2) {
                var myBarChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June"],
                        datasets: [{
                            label: "Revenue",
                            backgroundColor: "#4e73df",
                            hoverBackgroundColor: "#2e59d9",
                            borderColor: "#4e73df",
                            data: [4215, 5312, 6251, 7841, 9821, 14984],
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 6
                                },
                                maxBarThickness: 25,
                            },
                            y: {
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    // Include a dollar sign in the ticks
                                    callback: function(value, index, values) {
                                        return '$' + number_format(value);
                                    }
                                },
                                grid: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            },
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                titleMarginBottom: 10,
                                titleFont: {
                                    size: 14
                                },
                                backgroundColor: "rgb(255,255,255)",
                                bodyColor: "#858796",
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                displayColors: false,
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                        return datasetLabel + ': $' + number_format(tooltipItem.parsed.y);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Initialize DataTable
            $(document).ready(function() {
                $('#dataTable').DataTable();
                
                // Sidebar toggle functionality
                $("#sidebarToggleTop").on('click', function() {
                    $(".sidebar").toggleClass("show");
                });
            });
            
            // Number formatting function
            function number_format(number, decimals, dec_point, thousands_sep) {
                number = (number + '').replace(',', '').replace(' ', '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function(n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }
                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            }
        });
    </script>
</body>
</html>
