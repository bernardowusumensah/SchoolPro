<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Custom Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CSS CDN for production -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Scripts -->
        @if(file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link href="{{ asset('build/assets/app-t0uJuPf-.css') }}" rel="stylesheet">
            <script src="{{ asset('build/assets/app-CXDpL9bK.js') }}" defer></script>
        @endif
        
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Figtree', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                background-color: #f3f4f6;
                color: #111827;
                line-height: 1.5;
            }
            
            .min-h-screen { min-height: 100vh; }
            .flex { display: flex; }
            .flex-col { flex-direction: column; }
            .items-center { align-items: center; }
            .justify-center { justify-content: center; }
            .pt-6 { padding-top: 1.5rem; }
            .sm\:pt-0 { padding-top: 0; }
            .sm\:justify-center { justify-content: center; }
            .bg-gray-100 { background-color: #f3f4f6; }
            
            .w-full { width: 100%; }
            .w-20 { width: 5rem; }
            .h-20 { height: 5rem; }
            .object-contain { object-fit: contain; }
            
            .sm\:max-w-md { max-width: 28rem; }
            .mt-6 { margin-top: 1.5rem; }
            .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
            .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
            .bg-white { background-color: white; }
            .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
            .overflow-hidden { overflow: hidden; }
            .sm\:rounded-lg { border-radius: 0.5rem; }
            
            .block { display: block; }
            .mt-1 { margin-top: 0.25rem; }
            .mt-2 { margin-top: 0.5rem; }
            .mt-4 { margin-top: 1rem; }
            .mb-4 { margin-bottom: 1rem; }
            .ms-2 { margin-left: 0.5rem; }
            .ms-3 { margin-left: 0.75rem; }
            
            input[type="email"],
            input[type="password"],
            input[type="text"] {
                width: 100%;
                padding: 0.5rem 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
            
            input[type="email"]:focus,
            input[type="password"]:focus,
            input[type="text"]:focus {
                outline: none;
                border-color: #6366f1;
                ring: 2px;
                ring-color: #6366f1;
            }
            
            input[type="checkbox"] {
                border-radius: 0.25rem;
                border-color: #d1d5db;
            }
            
            label {
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                display: block;
                margin-bottom: 0.5rem;
            }
            
            .inline-flex {
                display: inline-flex;
            }
            
            .items-center {
                align-items: center;
            }
            
            .text-sm {
                font-size: 0.875rem;
            }
            
            .text-gray-600 {
                color: #4b5563;
            }
            
            .underline {
                text-decoration: underline;
            }
            
            a {
                color: #4b5563;
            }
            
            a:hover {
                color: #111827;
            }
            
            button,
            .btn {
                padding: 0.5rem 1rem;
                background-color: #4f46e5;
                color: white;
                border: none;
                border-radius: 0.375rem;
                font-weight: 500;
                font-size: 0.875rem;
                cursor: pointer;
            }
            
            button:hover,
            .btn:hover {
                background-color: #4338ca;
            }
            
            .flex.items-center.justify-end {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 0.75rem;
            }
            
            @media (min-width: 640px) {
                .sm\:max-w-md { max-width: 28rem; }
                .sm\:rounded-lg { border-radius: 0.5rem; }
                .sm\:justify-center { justify-content: center; }
                .sm\:pt-0 { padding-top: 0; }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                    <a href="/">
                        <img src="{{ asset('images/background.png') }}" alt="Logo" class="w-20 h-20 object-contain" />
                    </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
