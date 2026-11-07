<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shiro Laundry')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                        accent: '#3B82F6',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #3B82F6; border-radius: 50%; width: 24px; height: 24px; animation: spin 0.8s linear infinite; display: inline-block; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    @yield('content')

    <!-- Toast notification for success -->
    @if(session('success'))
    <div id="toast-success" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
        {{ session('success') }}
    </div>
    <script>setTimeout(() => document.getElementById('toast-success')?.remove(), 4000);</script>
    @endif

    <!-- Toast notification for error -->
    @if(session('error'))
    <div id="toast-error" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
        {{ session('error') }}
    </div>
    <script>setTimeout(() => document.getElementById('toast-error')?.remove(), 4000);</script>
    @endif

    @stack('scripts')
</body>
</html>
