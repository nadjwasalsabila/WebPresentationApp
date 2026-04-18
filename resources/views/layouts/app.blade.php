<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tutorial App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-blue-700 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('tutorials.index') }}" class="font-bold text-lg tracking-wide">
                📚 Tutorial App
            </a>
            <div class="flex items-center gap-4 text-sm">
                <span class="opacity-80">{{ session('user_email') }}</span>
                <a href="{{ route('tutorials.index') }}" class="hover:underline">Master Tutorial</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-white text-blue-700 font-semibold px-3 py-1 rounded hover:bg-blue-50 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="max-w-7xl mx-auto px-4 py-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-400 text-green-800 rounded-lg px-4 py-3 mb-4 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-400 text-red-800 rounded-lg px-4 py-3 mb-4 text-sm">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>