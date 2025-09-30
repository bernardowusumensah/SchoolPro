<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - SchoolPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fc;
        }
        
        ul.sidebar.navbar-nav {
            width: 250px !important;
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh;
            background: linear-gradient(180deg, #2c2c2c 10%, #1a1a1a 100%) !important;
            z-index: 1000 !important;
            transition: all 0.3s;
            overflow-y: auto;
            display: block !important;
            flex-direction: column !important;
            padding-left: 0 !important;
            margin-bottom: 0 !important;
            list-style: none !important;
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
            margin-left: 250px !important;
            min-height: 100vh;
            transition: all 0.3s;
            position: relative;
            z-index: 1;
            padding: 0 15px;
            width: calc(100% - 250px);
            box-sizing: border-box;
        }
        
        /* Container fluid adjustment */
        .content-wrapper .container-fluid {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
            max-width: none !important;
            width: 100% !important;
        }
        
        /* Navbar */
        .topbar {
            height: 80px;
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 0 1.5rem;
            width: 100%;
            margin: 0;
        }
        
        /* Page content spacing */
        .content-wrapper .container-fluid {
            padding-top: 1rem;
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
</head>
<body>
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <!-- Sidebar Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard/teacher">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SchoolPro</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="/dashboard/teacher">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Student Supervision
        </div>

        <!-- Nav Item - My Assigned Students -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudents"
                aria-expanded="true" aria-controls="collapseStudents">
                <i class="fas fa-fw fa-user-graduate"></i>
                <span>My Assigned Students</span>
            </a>
            <div id="collapseStudents" class="collapse" aria-labelledby="headingStudents" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Student Supervision:</h6>
                    <a class="collapse-item" href="/teacher/students">All Assigned Students</a>
                    <a class="collapse-item" href="/teacher/students/projects">Project Monitoring</a>
                    <a class="collapse-item" href="/teacher/students/progress">Progress Tracking</a>
                    <a class="collapse-item" href="/teacher/students/reports">Generate Reports</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Project Reviews -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReviews"
                aria-expanded="true" aria-controls="collapseReviews">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Project Reviews</span>
            </a>
            <div id="collapseReviews" class="collapse" aria-labelledby="headingReviews" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Review Tasks:</h6>
                    <a class="collapse-item" href="/teacher/proposals">Pending Proposals</a>
                    <a class="collapse-item" href="/teacher/proposals/approved">Approved Proposals</a>
                    <a class="collapse-item" href="/teacher/proposals/rejected">Rejected Proposals</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Weekly Logs -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLogs"
                aria-expanded="true" aria-controls="collapseLogs">
                <i class="fas fa-fw fa-book"></i>
                <span>Weekly Logs</span>
            </a>
            <div id="collapseLogs" class="collapse" aria-labelledby="headingLogs" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Log Management:</h6>
                    <a class="collapse-item" href="/teacher/logs/pending">Pending Review</a>
                    <a class="collapse-item" href="/teacher/logs/reviewed">Reviewed Logs</a>
                    <a class="collapse-item" href="/teacher/logs/feedback">Provide Feedback</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Final Submissions -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGrading"
                aria-expanded="true" aria-controls="collapseGrading">
                <i class="fas fa-fw fa-star"></i>
                <span>Final Submissions</span>
            </a>
            <div id="collapseGrading" class="collapse" aria-labelledby="headingGrading" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Grading:</h6>
                    <a class="collapse-item" href="/teacher/submissions/pending">Pending Grading</a>
                    <a class="collapse-item" href="/teacher/submissions/graded">Completed Grades</a>
                    <a class="collapse-item" href="/teacher/submissions/export">Export Records</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Communication & Reports
        </div>

        <!-- Nav Item - Student Reports -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports"
                aria-expanded="true" aria-controls="collapseReports">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>Student Reports</span>
            </a>
            <div id="collapseReports" class="collapse" aria-labelledby="headingReports" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Progress Reports:</h6>
                    <a class="collapse-item" href="/teacher/reports/progress">Student Progress</a>
                    <a class="collapse-item" href="/teacher/reports/behind">Students Behind</a>
                    <a class="collapse-item" href="/teacher/reports/export">Export Reports</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Notifications -->
        <li class="nav-item">
            <a class="nav-link" href="/teacher/notifications">
                <i class="fas fa-fw fa-bell"></i>
                <span>Notifications</span>
            </a>
        </li>

        <!-- Nav Item - Meeting Scheduler -->
        <li class="nav-item">
            <a class="nav-link" href="/teacher/meetings">
                <i class="fas fa-fw fa-calendar-plus"></i>
                <span>Schedule Meetings</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Nav Item - Profile -->
        <li class="nav-item">
            <a class="nav-link" href="/profile">
                <i class="fas fa-fw fa-user"></i>
                <span>My Profile</span>
            </a>
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
        <nav class="navbar navbar-expand topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search students, classes..."
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
                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">5</span>
                    </a>
                </li>

                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope fa-fw"></i>
                        <!-- Counter - Messages -->
                        <span class="badge badge-danger badge-counter">3</span>
                    </a>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Teacher' }}</span>
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
                        <a class="dropdown-item" href="/teacher/settings">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="/teacher/gradebook">
                            <i class="fas fa-book fa-sm fa-fw mr-2 text-gray-400"></i>
                            Gradebook
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
                <h1 class="h3 mb-0 text-gray-800">Teacher Dashboard</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i> Generate Class Report
                </a>
            </div>

            <!-- Hidden logout form for dropdown -->
            <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <!-- Content Row -->
            <div class="row">

                <!-- Assigned Students Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Assigned Students</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['assigned_students'] ?? '18' }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Proposals Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Proposals</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_proposals'] ?? '5' }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unreviewed Logs Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Unreviewed Logs</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['unreviewed_logs'] ?? '12' }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submissions to Grade Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Submissions to Grade</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_grading'] ?? '7' }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Student Progress Timeline -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Student Progress Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                            <div class="mt-4 text-center text-muted">
                                <small>Shows proposal submissions, log entries, and final submissions over time</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Status Distribution -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Project Status Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-pie">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center text-xs">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> In Progress
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-warning"></i> Proposal Review
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Ready for Grading
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Recent Supervision Activities -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Supervision Activities</h6>
                        </div>
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Proposals Reviewed This Week <span
                                    class="float-right">8 of 12</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 67%"
                                    aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Weekly Logs Reviewed <span
                                    class="float-right">15 of 18</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 83%"
                                    aria-valuenow="83" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Final Submissions Graded <span
                                    class="float-right">4 of 7</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 57%"
                                    aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Student Communications Sent <span
                                    class="float-right">23 messages</span></h4>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"
                                    aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            <a href="/teacher/proposals/pending" class="text-white text-decoration-none">
                                                <i class="fas fa-file-alt fa-fw"></i>
                                                Review Pending Proposals
                                                <div class="text-white-50 small">5 proposals awaiting review</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            <a href="/teacher/logs/pending" class="text-white text-decoration-none">
                                                <i class="fas fa-calendar-week fa-fw"></i>
                                                Review Weekly Logs
                                                <div class="text-white-50 small">12 logs need review</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            <a href="/teacher/submissions/pending" class="text-white text-decoration-none">
                                                <i class="fas fa-star fa-fw"></i>
                                                Grade Final Submissions
                                                <div class="text-white-50 small">7 submissions to grade</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            <a href="/teacher/students/communication" class="text-white text-decoration-none">
                                                <i class="fas fa-comments fa-fw"></i>
                                                Message Students
                                                <div class="text-white-50 small">Send updates and feedback</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        <div class="card-body">
                                            <a href="/teacher/assignments/create" class="text-white text-decoration-none">
                                                <i class="fas fa-plus fa-fw"></i>
                                                Create New Assignment
                                                <div class="text-white-50 small">Add a new project or task</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            <a href="/teacher/messages" class="text-white text-decoration-none">
                                                <i class="fas fa-envelope fa-fw"></i>
                                                Send Class Message
                                                <div class="text-white-50 small">Communicate with students</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            <a href="/teacher/reports" class="text-white text-decoration-none">
                                                <i class="fas fa-chart-bar fa-fw"></i>
                                                Generate Reports
                                                <div class="text-white-50 small">Student performance analytics</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Students Overview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">My Assigned Students - Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Project Title</th>
                                    <th>Project Status</th>
                                    <th>Last Log Entry</th>
                                    <th>Action Needed</th>
                                    <th>Quick Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($students) && count($students) > 0)
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->project_title ?? 'E-Commerce Platform' }}</td>
                                        <td>
                                            @if($student->project_status == 'Pending')
                                                <span class="badge bg-warning">Proposal Review</span>
                                            @elseif($student->project_status == 'Approved')
                                                <span class="badge bg-primary">In Progress</span>
                                            @elseif($student->project_status == 'Completed')
                                                <span class="badge bg-info">Ready for Grading</span>
                                            @elseif($student->project_status == 'Rejected')
                                                <span class="badge bg-danger">Proposal Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">Not Started</span>
                                            @endif
                                        </td>
                                        <td>{{ $student->last_log_date ?? '3 days ago' }}</td>
                                        <td>
                                            @if($student->action_needed == 'review_proposal')
                                                <span class="text-warning">Review Proposal</span>
                                            @elseif($student->action_needed == 'check_logs')
                                                <span class="text-info">Review Weekly Logs</span>
                                            @elseif($student->action_needed == 'grade_submission')
                                                <span class="text-success">Grade Final Work</span>
                                            @else
                                                <span class="text-muted">Monitor Progress</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/teacher/students/{{ $student->id }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Sarah Johnson</td>
                                        <td>E-Commerce Platform</td>
                                        <td><span class="badge bg-warning">Proposal Review</span></td>
                                        <td>2 days ago</td>
                                        <td><span class="text-warning">Review Proposal</span></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Michael Chen</td>
                                        <td>Task Management App</td>
                                        <td><span class="badge bg-primary">In Progress</span></td>
                                        <td>1 day ago</td>
                                        <td><span class="text-info">Review Weekly Logs</span></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Emma Rodriguez</td>
                                        <td>Social Media Dashboard</td>
                                        <td><span class="badge bg-info">Ready for Grading</span></td>
                                        <td>5 hours ago</td>
                                        <td><span class="text-success">Grade Final Work</span></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>David Kim</td>
                                        <td>Inventory System</td>
                                        <td><span class="badge bg-primary">In Progress</span></td>
                                        <td>1 day ago</td>
                                        <td><span class="text-muted">Monitor Progress</span></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Lisa Thompson</td>
                                        <td>Recipe Sharing Platform</td>
                                        <td><span class="badge bg-warning">Proposal Review</span></td>
                                        <td>3 days ago</td>
                                        <td><span class="text-warning">Review Proposal</span></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Teacher Notifications & Reminders -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Notifications & Reminders</h6>
                            <a href="/teacher/notifications" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle fa-fw"></i>
                                <strong>Reminder:</strong> 5 project proposals are awaiting your review. Please review by end of week.
                            </div>
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-calendar-check fa-fw"></i>
                                <strong>Update:</strong> Sarah Johnson submitted her weekly log entry. <a href="#" class="alert-link">Review now</a>
                            </div>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-star fa-fw"></i>
                                <strong>Action Needed:</strong> Emma Rodriguez submitted her final project for grading. <a href="#" class="alert-link">Grade submission</a>
                            </div>
                            <div class="alert alert-secondary" role="alert">
                                <i class="fas fa-bell fa-fw"></i>
                                <strong>Notice:</strong> Weekly supervision report is due tomorrow. <a href="#" class="alert-link">Generate report</a>
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
            // Student Progress Timeline Chart
            var ctx = document.getElementById("myAreaChart");
            if (ctx) {
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Week 1", "Week 2", "Week 3", "Week 4", "Week 5", "Week 6", "Week 7", "Week 8"],
                        datasets: [{
                            label: "Proposal Submissions",
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
                            data: [0, 2, 8, 12, 5, 3, 1, 0],
                        }, {
                            label: "Weekly Log Entries",
                            lineTension: 0.3,
                            backgroundColor: "rgba(28, 200, 138, 0.05)",
                            borderColor: "rgba(28, 200, 138, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(28, 200, 138, 1)",
                            pointBorderColor: "rgba(28, 200, 138, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                            pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: [0, 3, 15, 18, 18, 18, 17, 16],
                        }, {
                            label: "Final Submissions",
                            lineTension: 0.3,
                            backgroundColor: "rgba(231, 74, 59, 0.05)",
                            borderColor: "rgba(231, 74, 59, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(231, 74, 59, 1)",
                            pointBorderColor: "rgba(231, 74, 59, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(231, 74, 59, 1)",
                            pointHoverBorderColor: "rgba(231, 74, 59, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: [0, 0, 0, 0, 1, 3, 5, 8],
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
                                    maxTicksLimit: 7
                                }
                            },
                            y: {
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    callback: function(value, index, values) {
                                        callback: function(value, index, values) {
                                    return value + ' items';
                                }
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
                            }
                        }
                    }
                });
            }

            // Grade Distribution Pie Chart
            var ctx2 = document.getElementById("myPieChart");
            if (ctx2) {
                var myPieChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['A (90-100%)', 'B (80-89%)', 'C (70-79%)', 'D (60-69%)', 'F (<60%)'],
                        datasets: [{
                            data: [25, 35, 20, 15, 5],
                            backgroundColor: ['#1cc88a', '#4e73df', '#f6c23e', '#fd7e14', '#e74a3b'],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        cutout: 80,
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
        });
    </script>
</body>
</html>
