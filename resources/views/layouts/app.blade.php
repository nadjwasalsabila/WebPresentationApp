<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tutorial App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <style>
        /* ===== DataTables Custom Styling ===== */
        div.dataTables_wrapper {
            font-family: inherit;
            font-size: 0.875rem;
            color: #374151;
        }
        /* Top controls: search + length */
        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_length {
            margin-bottom: 1rem;
        }
        div.dataTables_wrapper div.dataTables_filter label,
        div.dataTables_wrapper div.dataTables_length label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4B5563;
            font-size: 0.875rem;
        }
        div.dataTables_wrapper div.dataTables_filter input {
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
        }
        div.dataTables_wrapper div.dataTables_filter input:focus {
            border-color: #6B7280;
            box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.2);
        }
        div.dataTables_wrapper div.dataTables_length select {
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
            outline: none;
            cursor: pointer;
        }
        /* Info text */
        div.dataTables_wrapper div.dataTables_info {
            color: #6B7280;
            font-size: 0.8125rem;
            padding-top: 0.5rem;
        }
        /* Pagination */
        div.dataTables_wrapper div.dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding-top: 0.25rem;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            height: 2rem;
            padding: 0 0.625rem;
            border-radius: 0.5rem;
            border: 1px solid #E5E7EB;
            background: #fff;
            color: #374151 !important;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none !important;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover:not(.disabled):not(.current) {
            background: #F3F4F6;
            border-color: #9CA3AF;
            color: #111827 !important;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current {
            background: #374151 !important;
            border-color: #374151 !important;
            color: #fff !important;
            font-weight: 600;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        /* Bottom: info + pagination side by side */
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1rem;
        }
        table.dataTable thead th {
            background-color: #F9FAFB;
            border-bottom: 2px solid #E5E7EB;
        }
        table.dataTable thead th.sorting::after,
        table.dataTable thead th.sorting_asc::after,
        table.dataTable thead th.sorting_desc::after {
            opacity: 0.6;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-gray-700 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('tutorials.index') }}" class="font-bold text-lg tracking-wide">
                Tutorial App
            </a>
            <div class="flex items-center gap-4 text-sm">
                <span class="opacity-80">{{ session('user_email') }}</span>
                <a href="{{ route('tutorials.index') }}" class="hover:underline">Master Tutorial</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-white text-gray-700 font-semibold px-3 py-1 rounded hover:bg-gray-50 transition">
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
            <div class="bg-gray-50 border border-gray-400 text-gray-800 rounded-lg px-4 py-3 mb-4 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-gray-50 border border-gray-400 text-gray-800 rounded-lg px-4 py-3 mb-4 text-sm">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
