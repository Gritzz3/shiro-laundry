@extends('layouts.app')

@section('title', 'Login - Shiro Laundry')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary to-accent px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary">Shiro Laundry</h1>
                <p class="text-gray-500 mt-1">Panel Administrasi</p>
            </div>

            {{-- Error Messages --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent transition"
                        placeholder="kasir@shiro.com"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent transition"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-accent focus:ring-accent">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200"
                >
                    Masuk
                </button>
            </form>

            {{-- Back to home --}}
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-primary transition">
                    ← Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
