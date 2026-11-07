@extends('layouts.app')

@section('title', 'Dashboard - Shiro Admin')

@section('content')
{{-- Top Navigation --}}
<nav class="bg-primary shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <h1 class="text-white font-bold text-xl">🧺 Shiro Laundry Admin</h1>
                @if($user->isSuperAdmin())
                <a href="{{ route('admin.users') }}" class="text-blue-200 hover:text-white text-sm transition">Kelola User</a>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-white text-sm font-medium">{{ $user->name }}</p>
                    <span class="text-xs px-2.5 py-0.5 rounded-full {{ $user->isSuperAdmin() ? 'bg-yellow-400 text-yellow-900' : 'bg-blue-300 text-blue-900' }} font-semibold">
                        {{ $user->isSuperAdmin() ? 'Super Admin' : 'Kasir' }}
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-blue-200 hover:text-white text-sm transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 py-6">
    {{-- Stats Cards --}}
    @if($stats)
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Order</p>
        </div>
        <div class="bg-yellow-50 rounded-xl shadow p-4 text-center border-l-4 border-yellow-400">
            <p class="text-2xl font-bold text-yellow-700">{{ $stats['antrean'] }}</p>
            <p class="text-xs text-yellow-600 mt-1">Antrean</p>
        </div>
        <div class="bg-blue-50 rounded-xl shadow p-4 text-center border-l-4 border-blue-400">
            <p class="text-2xl font-bold text-blue-700">{{ $stats['proses'] }}</p>
            <p class="text-xs text-blue-600 mt-1">Proses Cuci</p>
        </div>
        <div class="bg-purple-50 rounded-xl shadow p-4 text-center border-l-4 border-purple-400">
            <p class="text-2xl font-bold text-purple-700">{{ $stats['setrika'] }}</p>
            <p class="text-xs text-purple-600 mt-1">Setrika & Packing</p>
        </div>
        <div class="bg-green-50 rounded-xl shadow p-4 text-center border-l-4 border-green-400">
            <p class="text-2xl font-bold text-green-700">{{ $stats['selesai'] }}</p>
            <p class="text-xs text-green-600 mt-1">Selesai</p>
        </div>
    </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="🔍 Cari nomor nota atau nama pelanggan..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent text-sm"
                >
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent text-sm bg-white">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Order::STATUSES as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-primary hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                Filter
            </button>
            <a href="{{ route('admin.orders.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition text-center flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Order Baru
            </a>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">No. Nota</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Pelanggan</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Total Harga</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden md:table-cell">Update Terakhir</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-mono font-bold text-primary">{{ $order->nomor_nota }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $order->nama_pelanggan }}</p>
                            <p class="text-xs text-gray-400">{{ $order->no_hp }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'antrean' => 'bg-yellow-100 text-yellow-800',
                                    'proses_cuci' => 'bg-blue-100 text-blue-800',
                                    'setrika_packing' => 'bg-purple-100 text-purple-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status_cucian] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ \App\Models\Order::STATUSES[$order->status_cucian] ?? $order->status_cucian }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-gray-700">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-gray-500 text-xs">
                            {{ $order->updated_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($order->getNextStatus())
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="bg-accent hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap"
                                        onclick="return confirm('Update status ke {{ \App\Models\Order::STATUSES[$order->getNextStatus()] }}?')"
                                    >
                                        → {{ \App\Models\Order::STATUSES[$order->getNextStatus()] }}
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center gap-1 text-green-600 text-xs font-semibold">
                                    ✓ Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                            <p class="text-4xl mb-2">📭</p>
                            <p>Belum ada data order</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-4 py-3 border-t bg-gray-50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
