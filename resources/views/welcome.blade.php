<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolPro - Student Project Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            
            background-size: cover;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .landing-card {
            background: rgba(255,255,255,0.92);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            max-width: 420px;
            margin: 80px auto;
            padding: 40px 32px;
            text-align: center;
        }
        .logo {
            width: 80px;
            margin-bottom: 1rem;
        }
        h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            color: #222;
            font-weight: 700;
        }
        .subtitle {
            color: #555;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        .welcome {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #333;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .btn {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #1e40af;
        }
    </style>
</head>
<body>
    <div class="landing-card">
        <img src="/images/background.png" alt="SchoolPro Logo" class="logo">
        <h1>SchoolPro</h1>
        <div class="subtitle">A Student Project Management System</div>
        <div class="welcome">Welcome!</div>
        <div class="btn-group">
            <a href="{{ route('login') }}" class="btn">Login</a>
            <a href="{{ route('register') }}" class="btn">Register</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>