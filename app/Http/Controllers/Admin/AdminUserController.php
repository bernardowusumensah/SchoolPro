<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        // Authorization handled by route middleware: 'role:admin'
        $query = User::query();

        // Filter by role if specified
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        
        // Return HTML directly to bypass view issues
        $totalUsers = $users->count();
        $students = $users->where('role', 'student')->count();
        $teachers = $users->where('role', 'teacher')->count();
        $admins = $users->where('role', 'admin')->count();
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background: white; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; }
        .header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header p { margin: 0; color: #666; }
        .stats { display: flex; gap: 15px; margin-bottom: 15px; }
        .stat-box { background: white; padding: 15px; border: 1px solid #ddd; text-align: center; flex: 1; }
        .stat-box h3 { margin: 0 0 5px 0; font-size: 14px; color: #666; }
        .stat-box h2 { margin: 0; font-size: 20px; }
        table { width: 100%; background: white; border-collapse: collapse; border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; font-size: 14px; }
        .profile-pic { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .user-row { display: flex; align-items: center; }
        .user-info { margin-left: 10px; }
        .btn { padding: 6px 12px; background: #007bff; color: white; text-decoration: none; border: none; font-size: 12px; margin-right: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-primary { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; }
        .badge { padding: 3px 6px; font-size: 11px; font-weight: bold; border: 1px solid; }
        .badge-admin { background: #f8f9fa; color: #495057; border-color: #6c757d; }
    .badge-superadmin { background: #007bff; color: #fff; border-color: #0056b3; margin-left: 5px; }
        .badge-teacher { background: #fff3cd; color: #856404; border-color: #ffeaa7; }
        .badge-student { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .badge-active { background: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
        .badge-inactive { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .actions { margin-top: 15px; padding: 15px; background: white; border: 1px solid #ddd; }
        .actions h3 { margin: 0 0 10px 0; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Management</h1>
            <p>Total Users: ' . $totalUsers . '</p>
            <a href="' . route('admin.users.create') . '" class="btn btn-primary">Create New User</a>
        </div>

        <div class="stats">
            <div class="stat-box">
                <h3>Total</h3>
                <h2>' . $totalUsers . '</h2>
            </div>
            <div class="stat-box">
                <h3>Students</h3>
                <h2>' . $students . '</h2>
            </div>
            <div class="stat-box">
                <h3>Teachers</h3>
                <h2>' . $teachers . '</h2>
            </div>
            <div class="stat-box">
                <h3>Admins</h3>
                <h2>' . $admins . '</h2>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';
            
        foreach($users as $user) {
            // Determine profile picture path with role-based fallback
            if ($user->profile_picture) {
                $profilePic = '/images/profiles/' . $user->profile_picture;
                $fallbackPic = '/images/profiles/default-' . $user->role . '.svg';
            } else {
                $profilePic = '/images/profiles/default-' . $user->role . '.svg';
                $fallbackPic = '/images/profiles/default-admin.svg';
            }
            
            $html .= '<tr>
                <td>
                    <img src="' . $profilePic . '" alt="Profile" class="profile-pic" 
                         onerror="this.src=\'' . $fallbackPic . '\'">
                </td>
                <td>' . $user->id . '</td>
                <td>' . htmlspecialchars($user->name) . '</td>
                <td>' . htmlspecialchars($user->email) . '</td>
                <td>
                    <span class="badge badge-' . $user->role . '">' . strtoupper($user->role) . '</span>' .
                    ($user->super_admin ? ' <span class="badge badge-superadmin">SUPER ADMIN</span>' : '') . '
                </td>
                <td>
                    <span class="badge badge-' . $user->status . '">' . strtoupper($user->status) . '</span>
                </td>
                <td>' . $user->created_at->format('M d, Y') . '</td>
                <td>
                    <a href="' . route('admin.users.show', $user) . '" class="btn">View</a>';
                    
            if($user->id !== auth()->id()) {
                $html .= '<a href="' . route('admin.users.edit', $user) . '" class="btn btn-warning">Edit</a>';
                if($user->role !== 'admin') {
                    $html .= '<a href="#" onclick="deleteUser(' . $user->id . ')" class="btn btn-danger">Delete</a>';
                }
            }
            
            $html .= '</td>
            </tr>';
        }
        
        if($users->count() === 0) {
            $html .= '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #666;">No users found</td></tr>';
        }
        
        $html .= '</tbody>
        </table>

        <div class="actions">
            <h3>Quick Actions</h3>
            <a href="' . route('admin.users.create') . '" class="btn btn-primary">Add User</a>
            <a href="' . route('dashboard.admin') . '" class="btn">Back to Dashboard</a>
        </div>
    </div>

    <script>
        function deleteUser(id) {
            if(confirm("Are you sure you want to delete this user?")) {
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "/admin/users/" + id;
                
                var csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = "_token";
                csrfInput.value = "' . csrf_token() . '";
                
                var methodInput = document.createElement("input");
                methodInput.type = "hidden";
                methodInput.name = "_method";
                methodInput.value = "DELETE";
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>';

        return response($html);
    }

    /**
     * Show only deactivated users.
     */
    public function deactivated(Request $request)
    {
        $users = User::where('status', 'inactive')->orderBy('created_at', 'desc')->get();
        $html = '<!DOCTYPE html>
<html><head><title>Deactivated Users</title><style>body{font-family:Arial,sans-serif;margin:0;padding:20px;background:#f8f9fa;}.container{max-width:900px;margin:0 auto;}.header{background:white;padding:15px;margin-bottom:15px;border:1px solid #ddd;}.header h1{margin:0 0 5px 0;font-size:24px;}.header p{margin:0;color:#666;}table{width:100%;background:white;border-collapse:collapse;border:1px solid #ddd;}th,td{padding:10px;text-align:left;border-bottom:1px solid #eee;}th{background:#f8f9fa;font-weight:bold;font-size:14px;}.profile-pic{width:40px;height:40px;border-radius:50%;object-fit:cover;}.btn{padding:6px 12px;background:#007bff;color:white;text-decoration:none;border:none;font-size:12px;margin-right:5px;}.btn:hover{background:#0056b3;}.btn-success{background:#28a745;}.btn-danger{background:#dc3545;}.badge{padding:3px 6px;font-size:11px;font-weight:bold;border:1px solid;}.badge-admin{background:#f8f9fa;color:#495057;border-color:#6c757d;}.badge-teacher{background:#fff3cd;color:#856404;border-color:#ffeaa7;}.badge-student{background:#d4edda;color:#155724;border-color:#c3e6cb;}.badge-inactive{background:#f8d7da;color:#721c24;border-color:#f5c6cb;}</style></head><body><div class="container"><div class="header"><h1>Deactivated Users</h1><a href="' . route('dashboard.admin') . '" class="btn">Back to Dashboard</a></div><table><thead><tr><th>Profile</th><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead><tbody>';
        foreach($users as $user) {
            $profilePic = $user->profile_picture ? '/images/profiles/' . $user->profile_picture : '/images/profiles/default-' . $user->role . '.svg';
            $fallbackPic = '/images/profiles/default-' . $user->role . '.svg';
            $html .= '<tr><td><img src="' . $profilePic . '" alt="Profile" class="profile-pic" onerror="this.src=\'' . $fallbackPic . '\'"></td><td>' . $user->id . '</td><td>' . htmlspecialchars($user->name) . '</td><td>' . htmlspecialchars($user->email) . '</td><td><span class="badge badge-' . $user->role . '">' . strtoupper($user->role) . '</span></td><td><span class="badge badge-inactive">INACTIVE</span></td><td>' . $user->created_at->format('M d, Y') . '</td><td>';
            $html .= '<form method="POST" action="' . route('admin.users.activate', $user) . '" style="display:inline">' . csrf_field() . method_field('PATCH') . '<button type="submit" class="btn btn-success">Reactivate</button></form>';
            $html .= '<form method="POST" action="' . route('admin.users.destroy', $user) . '" style="display:inline" onsubmit="return confirm(\'Are you sure you want to permanently delete this user?\')">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger">Delete Permanently</button></form>';
            $html .= '</td></tr>';
        }
        if($users->count() === 0) {
            $html .= '<tr><td colspan="8" style="text-align:center;padding:40px;color:#666;">No deactivated users found</td></tr>';
        }
        $html .= '</tbody></table></div></body></html>';
        return response($html);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Authorization handled by route middleware: 'role:admin'
        // Only super admin can create admin users
        if (!auth()->user()->super_admin) {
            return response('Only the Super Admin can create new admin users.', 403);
        }
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: white; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; }
        .header h1 { margin: 0; font-size: 24px; }
        .form-container { background: white; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; box-sizing: border-box; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; text-decoration: none; margin-right: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .error { color: red; font-size: 12px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Create New User</h1>
            <a href="' . route('admin.users.index') . '" class="btn btn-secondary">Back to Users</a>
        </div>

        <div class="form-container">
            <form method="POST" action="' . route('admin.users.store') . '">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn">Create User</button>
                <a href="' . route('admin.users.index') . '" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>';

        return response($html);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        // Only super admin can create admin users
        if (!auth()->user()->super_admin) {
            return redirect()->route('admin.users.index')->with('error', 'Only the Super Admin can create new admin users.');
        }

        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Authorization handled by route middleware: 'role:admin'
        
        // Load user's projects if they exist
        $projects = collect();
        if ($user->role === 'student') {
            $projects = Project::where('student_id', $user->id)->get();
        } elseif ($user->role === 'teacher') {
            $projects = Project::where('supervisor_id', $user->id)->get();
        }

        // Return HTML directly to avoid view issues
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { background: white; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; }
        .header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .user-info { background: white; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; }
        .projects { background: white; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { font-weight: bold; width: 120px; }
        .info-value { flex: 1; }
        .profile-section { display: flex; align-items: center; margin-bottom: 20px; }
        .profile-pic-large { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-right: 20px; }
        .btn { padding: 8px 15px; background: #007bff; color: white; text-decoration: none; margin-right: 10px; }
        .btn-secondary { background: #6c757d; }
        .btn-warning { background: #ffc107; color: #212529; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: bold; }
        .badge { padding: 3px 6px; font-size: 11px; font-weight: bold; border: 1px solid; }
        .badge-approved { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .badge-pending { background: #fff3cd; color: #856404; border-color: #ffeaa7; }
        .badge-rejected { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Details</h1>
            <a href="' . route('admin.users.index') . '" class="btn btn-secondary">Back to Users</a>
            <a href="' . route('admin.users.edit', $user) . '" class="btn btn-warning">Edit User</a>
        </div>

        <div class="user-info">
            <h3>User Information</h3>
            <div class="profile-section">
                <img src="' . ($user->profile_picture ? '/images/profiles/' . $user->profile_picture : '/images/profiles/default-' . $user->role . '.svg') . '" 
                     alt="Profile Picture" class="profile-pic-large"
                     onerror="this.src=\'/images/profiles/default-' . $user->role . '.svg\'">
                <div>
                    <h2>' . htmlspecialchars($user->name) . '</h2>
                    <p>' . htmlspecialchars($user->email) . '</p>
                    <span class="badge badge-' . $user->role . '">' . strtoupper($user->role) . '</span>' .
                    ($user->super_admin ? ' <span class="badge badge-superadmin">SUPER ADMIN</span>' : '') . '
                    <span class="badge badge-' . $user->status . '">' . strtoupper($user->status) . '</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">ID:</div>
                <div class="info-value">' . $user->id . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Profile Picture:</div>
                <div class="info-value">' . ($user->profile_picture ?: 'Default') . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Created:</div>
                <div class="info-value">' . $user->created_at->format('M d, Y H:i') . '</div>
            </div>
            <div class="info-row">
                <div class="info-label">Updated:</div>
                <div class="info-value">' . $user->updated_at->format('M d, Y H:i') . '</div>
            </div>
        </div>';

        if ($projects->count() > 0) {
            $html .= '<div class="projects">
                <h3>Projects (' . $projects->count() . ')</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach($projects as $project) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($project->title) . '</td>
                    <td><span class="badge badge-' . strtolower($project->status) . '">' . $project->status . '</span></td>
                    <td>' . $project->created_at->format('M d, Y') . '</td>
                </tr>';
            }
            
            $html .= '</tbody>
                </table>
            </div>';
        } else {
            $html .= '<div class="projects">
                <h3>Projects</h3>
                <p>No projects found for this user.</p>
            </div>';
        }

        $html .= '</div>
</body>
</html>';

        return response($html);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Authorization handled by route middleware: 'role:admin'
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: white; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; }
        .header h1 { margin: 0; font-size: 24px; }
        .form-container { background: white; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; box-sizing: border-box; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; text-decoration: none; margin-right: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-warning { background: #ffc107; color: #212529; }
        .note { background: #f8f9fa; padding: 10px; border: 1px solid #ddd; margin-bottom: 15px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit User</h1>
            <a href="' . route('admin.users.index') . '" class="btn btn-secondary">Back to Users</a>
            <a href="' . route('admin.users.show', $user) . '" class="btn">View User</a>
        </div>

        <div class="form-container">
            <form method="POST" action="' . route('admin.users.update', $user) . '">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_method" value="PUT">
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="' . htmlspecialchars($user->name) . '" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="' . htmlspecialchars($user->email) . '" required>
                </div>

                <div class="note">
                    Leave password fields empty to keep current password
                </div>

                <div class="form-group">
                    <label for="password">New Password (optional)</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>';
                    
        if($user->role !== 'admin') {
            $html .= '<option value="student"' . ($user->role === 'student' ? ' selected' : '') . '>Student</option>
                     <option value="teacher"' . ($user->role === 'teacher' ? ' selected' : '') . '>Teacher</option>';
        } else {
            $html .= '<option value="admin" selected>Admin (Cannot be changed)</option>';
        }
        
        $html .= '</select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active"' . ($user->status === 'active' ? ' selected' : '') . '>Active</option>
                        <option value="inactive"' . ($user->status === 'inactive' ? ' selected' : '') . '>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Update User</button>
                <a href="' . route('admin.users.show', $user) . '" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>';

        return response($html);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        // Only update password if it's being changed
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Deactivate the specified user.
     */
    public function deactivate(User $user): RedirectResponse
    {
        // Authorization handled by route middleware: 'role:admin'
        // Additional check: prevent deactivating yourself
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['status' => 'inactive']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deactivated successfully.');
    }

    /**
     * Activate the specified user.
     */
    public function activate(User $user): RedirectResponse
    {
        // Authorization handled by route middleware: 'role:admin'
        $user->update(['status' => 'active']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User activated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Only super admin can delete admin users
        if (!auth()->user()->super_admin) {
            return redirect()->route('admin.users.index')->with('error', 'Only the Super Admin can delete admin users.');
        }
        // Prevent deleting yourself
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        // Prevent deleting the super admin
        if ($user->super_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'The Super Admin account cannot be deleted.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}