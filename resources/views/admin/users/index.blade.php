<!DOCTYPE html><!DOCTYPE html><!DOCTYPE html>

<html>

<head><html lang="en"><html lang="en">

    <title>User Management</title>

    <style><head><head>

        body { font-family: Arial; margin: 20px; background: #f5f5f5; }

        .container { max-width: 1200px; margin: 0 auto; }    <meta charset="UTF-8">    <meta charset="UTF-8">

        .header { background: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; }

        .stats { display: flex; gap: 20px; margin-bottom: 20px; }    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        .stat-box { background: white; padding: 20px; border-radius: 5px; text-align: center; flex: 1; }

        table { width: 100%; background: white; border-collapse: collapse; border-radius: 5px; overflow: hidden; }    <title>SchoolPro - User Management</title>    <title>SchoolPro - User Management</title>

        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }

        th { background: #f8f9fa; font-weight: bold; }    <style>    <style>

        .btn { padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; margin-right: 5px; }

        .btn-success { background: #28a745; }        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }

        .btn-warning { background: #ffc107; color: #212529; }

        .btn-danger { background: #dc3545; }        .header { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }        .header { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

        .badge { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }

        .badge-admin { background: #e3f2fd; color: #1565c0; }        .header h1 { margin: 0; color: #333; }        .header h1 { margin: 0; color: #333; }

        .badge-teacher { background: #fff3e0; color: #e65100; }

        .badge-student { background: #e8f5e8; color: #2e7d32; }        .stats { display: flex; gap: 20px; margin-bottom: 20px; }        .stats { display: flex; gap: 20px; margin-bottom: 20px; }

        .badge-active { background: #d4edda; color: #155724; }

        .badge-inactive { background: #f8d7da; color: #721c24; }        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }

    </style>

</head>        .user-table { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }        .user-table { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }

<body>

    <div class="container">        table { width: 100%; border-collapse: collapse; }        table { width: 100%; border-collapse: collapse; }

        <div class="header">

            <h1>👥 User Management</h1>        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }

            <p>Total Users: {{ $users->total() }}</p>

            <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Create New User</a>        th { background-color: #f8f9fa; font-weight: bold; }        th { background-color: #f8f9fa; font-weight: bold; }

        </div>

        .btn { display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 2px; border: none; cursor: pointer; }        .btn { display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 2px; }

        <div class="stats">

            <div class="stat-box">        .btn:hover { background: #0056b3; }        .btn:hover { background: #0056b3; }

                <h3>📊 Total</h3>

                <h2>{{ $users->total() }}</h2>        .btn-success { background: #28a745; }        .btn-success { background: #28a745; }

            </div>

            <div class="stat-box">        .btn-warning { background: #ffc107; color: #212529; }        .btn-warning { background: #ffc107; color: #212529; }

                <h3>👨‍🎓 Students</h3>

                <h2>{{ $users->where('role', 'student')->count() }}</h2>        .btn-danger { background: #dc3545; }        .btn-danger { background: #dc3545; }

            </div>

            <div class="stat-box">        .role-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }        .role-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }

                <h3>👨‍🏫 Teachers</h3>

                <h2>{{ $users->where('role', 'teacher')->count() }}</h2>        .role-admin { background: #e3f2fd; color: #1565c0; }        .role-admin { background: #e3f2fd; color: #1565c0; }

            </div>

            <div class="stat-box">        .role-teacher { background: #fff3e0; color: #e65100; }        .role-teacher { background: #fff3e0; color: #e65100; }

                <h3>👨‍💼 Admins</h3>

                <h2>{{ $users->where('role', 'admin')->count() }}</h2>        .role-student { background: #e8f5e8; color: #2e7d32; }        .role-student { background: #e8f5e8; color: #2e7d32; }

            </div>

        </div>        .status-active { background: #e8f5e8; color: #2e7d32; }        .status-active { background: #e8f5e8; color: #2e7d32; }



        <table>        .status-inactive { background: #ffebee; color: #c62828; }        .status-inactive { background: #ffebee; color: #c62828; }

            <thead>

                <tr>    </style>    </style>

                    <th>ID</th>

                    <th>Name</th></head></head>

                    <th>Email</th>

                    <th>Role</th><body><body>

                    <th>Status</th>

                    <th>Created</th>    <div class="header">    <div class="header">

                    <th>Actions</th>

                </tr>        <h1>👥 User Management System</h1>        <h1>👥 User Management System</h1>

            </thead>

            <tbody>        <p>Manage all users in the SchoolPro system</p>        <p>Manage all users in the SchoolPro system</p>

                <?php foreach($users as $user): ?>

                <tr>        <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Create New User</a>        <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Create New User</a>

                    <td><?= $user->id ?></td>

                    <td><?= htmlspecialchars($user->name) ?></td>    </div>    </div>

                    <td><?= htmlspecialchars($user->email) ?></td>

                    <td>

                        <span class="badge badge-<?= $user->role ?>">

                            <?= strtoupper($user->role) ?>    <div class="stats">    <div class="stats">

                        </span>

                    </td>        <div class="stat-card">        <div class="stat-card">

                    <td>

                        <span class="badge badge-<?= $user->status ?>">            <h3>📊 Total Users</h3>            <h3>📊 Total Users</h3>

                            <?= strtoupper($user->status) ?>

                        </span>            <h2>{{ $users->total() }}</h2>            <h2>{{ $users->total() }}</h2>

                    </td>

                    <td><?= $user->created_at->format('M d, Y') ?></td>        </div>        </div>

                    <td>

                        <a href="{{ route('admin.users.show', <?= $user->id ?>) }}" class="btn">View</a>        <div class="stat-card">        <div class="stat-card">

                        <?php if($user->id !== auth()->id()): ?>

                            <a href="{{ route('admin.users.edit', <?= $user->id ?>) }}" class="btn btn-warning">Edit</a>            <h3>👨‍🎓 Students</h3>            <h3>👨‍🎓 Students</h3>

                            <?php if($user->role !== 'admin'): ?>

                                <a href="#" onclick="deleteUser(<?= $user->id ?>, '<?= htmlspecialchars($user->name) ?>')" class="btn btn-danger">Delete</a>            <h2>{{ $users->where('role', 'student')->count() }}</h2>            <h2>{{ $users->where('role', 'student')->count() }}</h2>

                            <?php endif; ?>

                        <?php endif; ?>        </div>        </div>

                    </td>

                </tr>        <div class="stat-card">        <div class="stat-card">

                <?php endforeach; ?>

                            <h3>👨‍🏫 Teachers</h3>            <h3>👨‍🏫 Teachers</h3>

                <?php if($users->count() === 0): ?>

                <tr>            <h2>{{ $users->where('role', 'teacher')->count() }}</h2>            <h2>{{ $users->where('role', 'teacher')->count() }}</h2>

                    <td colspan="7" style="text-align: center; padding: 40px; color: #666;">

                        No users found        </div>        </div>

                    </td>

                </tr>        <div class="stat-card">        <div class="stat-card">

                <?php endif; ?>

            </tbody>            <h3>👨‍💼 Admins</h3>            <h3>👨‍💼 Admins</h3>

        </table>

            <h2>{{ $users->where('role', 'admin')->count() }}</h2>            <h2>{{ $users->where('role', 'admin')->count() }}</h2>

        <div style="margin-top: 20px; text-align: center;">

            <?php if($users->hasPages()): ?>        </div>        </div>

                {{ $users->links() }}

            <?php endif; ?>    </div>    </div>

        </div>



        <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 5px;">

            <h3>Quick Actions</h3>    <div class="user-table">    <div class="user-table">

            <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Add Student</a>

            <a href="{{ route('admin.users.create') }}" class="btn btn-success">👨‍🏫 Add Teacher</a>        <table>        <table>

            <a href="{{ route('dashboard.admin') }}" class="btn">🏠 Back to Dashboard</a>

        </div>            <thead>            <thead>

    </div>

                <tr>                <tr>

    <script>

        function deleteUser(id, name) {                    <th>ID</th>                    <th>ID</th>

            if(confirm('Are you sure you want to delete ' + name + '?')) {

                // Create form and submit                    <th>Name</th>                    <th>Name</th>

                var form = document.createElement('form');

                form.method = 'POST';                    <th>Email</th>                    <th>Email</th>

                form.action = '/admin/users/' + id;

                                    <th>Role</th>                    <th>Role</th>

                var csrfInput = document.createElement('input');

                csrfInput.type = 'hidden';                    <th>Status</th>                    <th>Status</th>

                csrfInput.name = '_token';

                csrfInput.value = '{{ csrf_token() }}';                    <th>Created</th>                    <th>Created</th>

                

                var methodInput = document.createElement('input');                    <th>Actions</th>                    <th>Actions</th>

                methodInput.type = 'hidden';

                methodInput.name = '_method';                </tr>                </tr>

                methodInput.value = 'DELETE';

                            </thead>            </thead>

                form.appendChild(csrfInput);

                form.appendChild(methodInput);            <tbody>            <tbody>

                document.body.appendChild(form);

                form.submit();                @forelse($users as $user)                @forelse($users as $user)

            }

        }                    <tr>                    <tr>

    </script>

</body>                        <td>{{ $user->id }}</td>                        <td>{{ $user->id }}</td>

</html>
                        <td>{{ $user->name }}</td>                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>                        <td>{{ $user->email }}</td>

                        <td>                        <td>

                            <span class="role-badge role-{{ $user->role }}">                            <span class="role-badge role-{{ $user->role }}">

                                {{ strtoupper($user->role) }}                                {{ strtoupper($user->role) }}

                            </span>                            </span>

                        </td>                        </td>

                        <td>                        <td>

                            <span class="role-badge status-{{ $user->status }}">                            <span class="role-badge status-{{ $user->status }}">

                                {{ strtoupper($user->status) }}                                {{ strtoupper($user->status) }}

                            </span>                            </span>

                        </td>                        </td>

                        <td>{{ $user->created_at->format('M d, Y') }}</td>                        <td>{{ $user->created_at->format('M d, Y') }}</td>

                        <td>                        <td>

                            <a href="{{ route('admin.users.show', $user) }}" class="btn">View</a>                            <a href="{{ route('admin.users.show', $user) }}" class="btn">View</a>

                            @if($user->id !== auth()->id())                            @if($user->id !== auth()->id())

                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>

                                @if($user->role !== 'admin')                                @if($user->role !== 'admin')

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">

                                        @csrf                                        @csrf

                                        @method('DELETE')                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger"                                         <button type="submit" class="btn btn-danger" 

                                                onclick="return confirm('Delete {{ $user->name }}?')">Delete</button>                                                onclick="return confirm('Delete {{ $user->name }}?')">Delete</button>

                                    </form>                                    </form>

                                @endif                                @endif

                            @endif                            @endif

                        </td>                        </td>

                    </tr>                    </tr>

                @empty                @empty

                    <tr>                    <tr>

                        <td colspan="7" style="text-align: center; padding: 40px; color: #666;">                        <td colspan="7" style="text-align: center; padding: 40px; color: #666;">

                            No users found                            No users found

                        </td>                        </td>

                    </tr>                    </tr>

                @endforelse                @endforelse

            </tbody>            </tbody>

        </table>        </table>

    </div>    </div>



    <div style="margin-top: 20px; text-align: center;">    <div style="margin-top: 20px; text-align: center;">

        @if($users->hasPages())        {{ $users->links() }}

            {{ $users->links() }}    </div>

        @endif

    </div>    <div style="margin-top: 20px; padding: 20px; background: white; border-radius: 8px;">

        <h3>Quick Actions</h3>

    <div style="margin-top: 20px; padding: 20px; background: white; border-radius: 8px;">        <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Add Student</a>

        <h3>Quick Actions</h3>        <a href="{{ route('admin.users.create') }}" class="btn btn-success">👨‍🏫 Add Teacher</a>

        <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Add Student</a>        <a href="{{ route('dashboard.admin') }}" class="btn">🏠 Back to Dashboard</a>

        <a href="{{ route('admin.users.create') }}" class="btn btn-success">👨‍🏫 Add Teacher</a>    </div>

        <a href="{{ route('dashboard.admin') }}" class="btn">🏠 Back to Dashboard</a></body>

    </div></html>

</body>                    <!-- Success/Error Messages -->

</html>                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                       placeholder="Name or email..." 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="role" name="role" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Roles</option>
                                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Filter
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Debug Info -->
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>Debug:</strong> Found {{ $users->count() }} users on current page ({{ $users->total() }} total)
                        </p>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                   ($user->role === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="text-blue-600 hover:text-blue-900">View</a>
                                                
                                                @if($user->id !== auth()->id())
                                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    
                                                    @if($user->status === 'active')
                                                        <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900"
                                                                    onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                                Deactivate
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.users.activate', $user) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                                Activate
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($user->role !== 'admin')
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>