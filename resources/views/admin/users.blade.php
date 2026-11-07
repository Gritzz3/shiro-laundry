@extends('layouts.app')

@section('title', 'Kelola User - Shiro Admin')

@section('content')
{{-- Top Navigation --}}
<nav class="bg-primary shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <h1 class="text-white font-bold text-xl">🧺 Shiro Laundry Admin</h1>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-200 hover:text-white text-sm transition">Dashboard</a>
                <span class="text-blue-300 text-sm">/ Kelola User</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-200 hover:text-white text-sm transition">Keluar</button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-4xl mx-auto px-4 py-6">
    {{-- Add User Form --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">➕ Tambah User Baru</h2>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Staff Shiro" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent text-sm" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="staff@shiro.com" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent text-sm" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent text-sm" minlength="8" required>
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role / Peran</label>
                    <select id="role" name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent text-sm bg-white" required>
                        <option value="kasir">Kasir</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-primary hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    Simpan User
                </button>
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-bold text-gray-800">👥 Daftar Pengguna Shiro Laundry ({{ $users->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Email</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Role</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $u->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $u->isSuperAdmin() ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $u->isSuperAdmin() ? 'Super Admin' : 'Kasir' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.delete', $u) }}" class="inline" onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">
                                    Hapus
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 text-xs italic">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
