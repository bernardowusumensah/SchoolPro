{{-- Standalone no-project page for deliverables, does not extend dashboard.student --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Approved Project - SchoolPro</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5 text-center">
        <h3 class="mb-4">No Approved Project Found</h3>
        <p class="text-muted">You need to have at least one approved project before you can view or upload deliverables.</p>
        <a href="{{ route('student.projects.index') }}" class="btn btn-primary mt-3">View My Projects</a>
    </div>
</body>
</html>
