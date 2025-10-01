<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SchoolPro</title>
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
</head>
<body>
    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <!-- Sidebar Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard/student">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SchoolPro</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="/dashboard/student">
                <i class="fas fa-fw fa-tachometer-alt"></i>
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
                <i class="fas fa-fw fa-project-diagram"></i>
                <span>My Project</span>
            </a>
            <div id="collapseProject" class="collapse" aria-labelledby="headingProject" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Project Management:</h6>
                    <a class="collapse-item" href="/student/project/proposal">Create Proposal</a>
                    <a class="collapse-item" href="/student/project/edit">Edit Proposal</a>
                    <a class="collapse-item" href="/student/project/status">Proposal Status</a>
                    <a class="collapse-item" href="/student/project/final">Final Submission</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Weekly Logs -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLogs"
                aria-expanded="true" aria-controls="collapseLogs">
                <i class="fas fa-fw fa-calendar-week"></i>
                <span>Weekly Progress Logs</span>
            </a>
            <div id="collapseLogs" class="collapse" aria-labelledby="headingLogs" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Progress Tracking:</h6>
                    <a class="collapse-item" href="/student/logs/upload">Upload New Log</a>
                    <a class="collapse-item" href="/student/logs/history">Log History</a>
                    <a class="collapse-item" href="/student/logs/feedback">Supervisor Feedback</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Deliverables -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDeliverables"
                aria-expanded="true" aria-controls="collapseDeliverables">
                <i class="fas fa-fw fa-upload"></i>
                <span>Project Deliverables</span>
            </a>
            <div id="collapseDeliverables" class="collapse" aria-labelledby="headingDeliverables" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Submissions:</h6>
                    <a class="collapse-item" href="/student/deliverables/final">Final Project</a>
                    <a class="collapse-item" href="/student/deliverables/documentation">Documentation</a>
                    <a class="collapse-item" href="/student/deliverables/presentation">Presentation</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - My Grades & Analytics -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAnalytics"
                aria-expanded="true" aria-controls="collapseAnalytics">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Progress Analytics</span>
            </a>
            <div id="collapseAnalytics" class="collapse" aria-labelledby="headingAnalytics" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Performance:</h6>
                    <a class="collapse-item" href="/student/analytics/progress">Progress Dashboard</a>
                    <a class="collapse-item" href="/student/analytics/grades">My Grades</a>
                    <a class="collapse-item" href="/student/analytics/deadlines">Deadlines</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Communication & Support
        </div>

        <!-- Nav Item - Supervisor Communication -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseComm"
                aria-expanded="true" aria-controls="collapseComm">
                <i class="fas fa-fw fa-comments"></i>
                <span>Supervisor Chat</span>
            </a>
            <div id="collapseComm" class="collapse" aria-labelledby="headingComm" data-bs-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Communication:</h6>
                    <a class="collapse-item" href="/student/chat">Chat with Supervisor</a>
                    <a class="collapse-item" href="/student/questions">Ask Questions</a>
                    <a class="collapse-item" href="/student/meetings">Schedule Meeting</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Notifications -->
        <li class="nav-item">
            <a class="nav-link" href="/student/notifications">
                <i class="fas fa-fw fa-bell"></i>
                <span>Notifications</span>
                <span class="badge bg-danger badge-counter">3</span>
            </a>
        </li>

        <!-- Nav Item - Group Projects -->
        <li class="nav-item">
            <a class="nav-link" href="/student/groups">
                <i class="fas fa-fw fa-users"></i>
                <span>Group Projects</span>
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
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search projects, grades..."
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
                        <span class="badge badge-danger badge-counter">2</span>
                    </a>
                </li>

                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope fa-fw"></i>
                        <!-- Counter - Messages -->
                        <span class="badge badge-danger badge-counter">1</span>
                    </a>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Student' }}</span>
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
                        <a class="dropdown-item" href="/student/settings">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="/student/progress">
                            <i class="fas fa-chart-line fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Progress
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
                <h1 class="h3 mb-0 text-gray-800">Student Dashboard</h1>
            </div>

            <!-- Hidden logout form for dropdown -->
            <form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <!-- Content Row -->
            <div class="row">

                <!-- Total Projects Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Projects</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $allProjects->count() ?? 0 }}</div>
                                    <div class="text-xs text-muted">All submitted projects</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approved Projects Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Approved Projects</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projectStats['approved'] ?? 0 }}</div>
                                    <div class="text-xs text-muted">Ready for development</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Needs Revision Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Needs Revision</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projectStats['rejected'] ?? 0 }}</div>
                                    <div class="text-xs text-muted">Requires updates</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Projects Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed Projects</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projectStats['completed'] ?? 0 }}</div>
                                    <div class="text-xs text-muted">Finished submissions</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-flag-checkered fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Assignments Card -->
                
            </div>



            <!-- All Projects Overview Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-list mr-2"></i>All My Projects
                            </h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="projectsDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="projectsDropdown">
                                    <div class="dropdown-header">Project Actions:</div>
                                    <a class="dropdown-item" href="{{ route('student.projects.proposal') }}">
                                        <i class="fas fa-plus mr-2"></i>Create New Project
                                    </a>
                                    <a class="dropdown-item" href="/student/project/guidelines">
                                        <i class="fas fa-info-circle mr-2"></i>View Guidelines
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($allProjects && $allProjects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="projectsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Project Title</th>
                                                <th>Status</th>
                                                <th>Supervisor</th>
                                                <th>Submitted</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allProjects as $project)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0 font-weight-bold text-gray-800">{{ $project->title }}</h6>
                                                            <small class="text-muted">{{ Str::limit($project->description, 60) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($project->status === 'Pending')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock mr-1"></i>Pending Review
                                                        </span>
                                                    @elseif($project->status === 'Approved')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle mr-1"></i>Approved
                                                        </span>
                                                    @elseif($project->status === 'Rejected')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle mr-1"></i>Needs Revision
                                                        </span>
                                                    @elseif($project->status === 'Completed')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-flag-checkered mr-1"></i>Completed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-question mr-1"></i>{{ $project->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($project->supervisor)
                                                        <div class="d-flex align-items-center">
                                                            <img class="rounded-circle mr-2" 
                                                                 src="{{ $project->supervisor->profile_picture_url ?? '/images/default-avatar.png' }}" 
                                                                 alt="Supervisor" 
                                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                                            <span class="text-gray-800">{{ $project->supervisor->name }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-gray-800">{{ $project->created_at->format('M j, Y') }}</span>
                                                    <br><small class="text-muted">{{ $project->created_at->format('g:i A') }}</small>
                                                </td>
                                                <td>
                                                    <span class="text-gray-800">{{ $project->updated_at->format('M j, Y') }}</span>
                                                    <br><small class="text-muted">{{ $project->updated_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('student.projects.show', $project->id) }}" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if(in_array($project->status, ['Pending', 'Rejected']))
                                                            <a href="{{ route('student.projects.editProject', $project->id) }}" 
                                                               class="btn btn-outline-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif
                                                        @if($project->status === 'Approved')
                                                            <a href="/student/logs/upload" 
                                                               class="btn btn-outline-success btn-sm">
                                                                <i class="fas fa-plus"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                

                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-project-diagram fa-4x text-gray-300 mb-3"></i>
                                    <h5 class="text-gray-600">No Projects Yet</h5>
                                    <p class="text-muted mb-4">You haven't created any project proposals yet. Get started with your capstone project!</p>
                                    <a href="{{ route('student.projects.proposal') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i>Create Your First Project
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts removed for cleaner UI/UX -->
          
                      
      

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
            // Initialize DataTable
            $(document).ready(function() {
                $('#dataTable').DataTable();
                
                // Initialize Projects Table
                $('#projectsTable').DataTable({
                    "order": [[ 4, "desc" ]], // Sort by Last Updated column (index 4) descending
                    "pageLength": 10,
                    "responsive": true,
                    "language": {
                        "search": "Search projects:",
                        "lengthMenu": "Show _MENU_ projects per page",
                        "info": "Showing _START_ to _END_ of _TOTAL_ projects",
                        "emptyTable": "No projects found"
                    },
                    "columnDefs": [
                        { "orderable": false, "targets": 5 } // Disable sorting on Actions column
                    ]
                });
                
                // Sidebar toggle functionality
                $("#sidebarToggleTop").on('click', function() {
                    $(".sidebar").toggleClass("show");
                });
            });
        });
    </script>
</body>
</html>
