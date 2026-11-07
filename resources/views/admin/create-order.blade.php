@extends('layouts.app')

@section('title', 'Buat Order Baru - Shiro Admin')

@section('content')
{{-- Top Navigation --}}
<nav class="bg-primary shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <h1 class="text-white font-bold text-xl">🧺 Shiro Laundry Admin</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-200 hover:text-white text-sm transition">← Kembali</a>
        </div>
    </div>
</nav>

<div class="max-w-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-1">📝 Buat Order Baru</h2>
        <p class="text-gray-500 text-sm mb-6">Nomor nota (format: SHR-XXXX) dibuat otomatis oleh sistem</p>

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf

            <div class="mb-4">
                <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                <input
                    type="text"
                    id="nama_pelanggan"
                    name="nama_pelanggan"
                    value="{{ old('nama_pelanggan') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"
                    placeholder="Contoh: Budi Santoso"
                    required
                    autofocus
                >
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP / WhatsApp Pelanggan</label>
                <input
                    type="tel"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"
                    placeholder="Contoh: 081234567890"
                    required
                >
            </div>

            <div class="mb-6">
                <label for="total_harga" class="block text-sm font-medium text-gray-700 mb-1">Total Biaya Cucian</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-500 font-medium">Rp</span>
                    <input
                        type="number"
                        id="total_harga"
                        name="total_harga"
                        value="{{ old('total_harga') }}"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent"
                        placeholder="50000"
                        min="0"
                        step="1000"
                        required
                    >
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button
                    type="submit"
                    class="flex-1 bg-primary hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200"
                >
                    Simpan Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
