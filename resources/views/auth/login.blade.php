<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Tutorial App</title>
    {{-- Pakai Tailwind CDN untuk tampilan --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">

    {{-- Header --}}
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tutorial App</h1>
        <p class="text-gray-500 text-sm mt-1">Masuk untuk mengelola tutorial</p>
    </div>

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Alert Sukses (setelah logout) --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Form Login --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="contoh@email.com"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                required
            >
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
                type="password"
                name="password"
                placeholder="••••••••"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                required
            >
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Login --}}
        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150"
        >
            Masuk
        </button>
    </form>

    {{-- Info akun demo --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        Demo: aprilyani.safitri@gmail.com / 123456
    </p>

</div>
</body>
</html>