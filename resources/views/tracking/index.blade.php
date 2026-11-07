@extends('layouts.app')

@section('title', 'Shiro Laundry - Cek Status Cucian')

@section('content')
{{-- Hero Section --}}
<div class="bg-gradient-to-br from-primary to-accent min-h-[45vh] flex items-center justify-center relative overflow-hidden">
    {{-- Background pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 text-white text-8xl">🧺</div>
        <div class="absolute bottom-10 right-10 text-white text-8xl">✨</div>
        <div class="absolute top-20 right-20 text-white text-6xl">👕</div>
    </div>

    <div class="text-center px-4 relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">
            Shiro Laundry
        </h1>
        <p class="text-blue-100 text-lg md:text-xl mb-2">Laundry Bersih, Wangi & Tepat Waktu</p>
        <p class="text-blue-200 text-sm md:text-base">Cek status cucian Anda secara real-time</p>
    </div>
</div>

{{-- Tracking Form Section --}}
<div class="max-w-lg mx-auto -mt-12 px-4 relative z-20">
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-1 text-center">📋 Lacak Cucian Anda</h2>
        <p class="text-gray-500 text-sm text-center mb-6">Masukkan nomor nota untuk melihat status</p>

        <form id="tracking-form" onsubmit="return handleTracking(event)">
            <div class="mb-4">
                <label for="nomor_nota" class="block text-sm font-medium text-gray-700 mb-1">Nomor Nota</label>
                <input
                    type="text"
                    id="nomor_nota"
                    name="nomor_nota"
                    placeholder="Contoh: SHR-0001"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent transition uppercase"
                    required
                    autocomplete="off"
                >
            </div>

            <div class="mb-4">
                <label for="verifikasi_hp" class="block text-sm font-medium text-gray-700 mb-1">
                    4 Digit Terakhir No. HP <span class="text-gray-400">(opsional)</span>
                </label>
                <input
                    type="text"
                    id="verifikasi_hp"
                    name="verifikasi_hp"
                    placeholder="Contoh: 7801"
                    maxlength="4"
                    pattern="\d{4}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent transition"
                    autocomplete="off"
                >
            </div>

            <button
                type="submit"
                id="btn-search"
                class="w-full bg-primary hover:bg-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span id="btn-text">Cari Status Cucian</span>
                <span id="btn-spinner" class="spinner hidden"></span>
            </button>
        </form>
    </div>
</div>

{{-- Results Area --}}
<div id="result-area" class="max-w-lg mx-auto px-4 mt-6 hidden">
    {{-- Error Message --}}
    <div id="error-message" class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl hidden fade-in">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span id="error-text"></span>
        </div>
    </div>

    {{-- Success Result --}}
    <div id="success-result" class="bg-white rounded-2xl shadow-xl p-6 md:p-8 hidden fade-in">
        {{-- Customer Info --}}
        <div class="text-center mb-6">
            <p class="text-sm text-gray-500">Nota Cucian</p>
            <h3 id="res-nota" class="text-2xl font-bold text-primary"></h3>
            <p id="res-nama" class="text-gray-700 mt-1 font-medium"></p>
        </div>

        {{-- Progress Bar --}}
        <div class="mb-6">
            <div class="flex items-center justify-between relative">
                {{-- Connecting line --}}
                <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 mx-8"></div>
                <div id="progress-line" class="absolute top-5 left-0 h-0.5 bg-accent mx-8 transition-all duration-500" style="width: 0%"></div>

                {{-- Step 1: Antrean --}}
                <div class="flex flex-col items-center relative z-10 step-item" data-step="1">
                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center bg-white transition-all step-circle">
                        <span class="text-sm">1</span>
                    </div>
                    <span class="text-xs mt-2 text-center font-medium step-label">Antrean</span>
                </div>

                {{-- Step 2: Proses Cuci --}}
                <div class="flex flex-col items-center relative z-10 step-item" data-step="2">
                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center bg-white transition-all step-circle">
                        <span class="text-sm">2</span>
                    </div>
                    <span class="text-xs mt-2 text-center font-medium step-label">Cuci</span>
                </div>

                {{-- Step 3: Setrika --}}
                <div class="flex flex-col items-center relative z-10 step-item" data-step="3">
                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center bg-white transition-all step-circle">
                        <span class="text-sm">3</span>
                    </div>
                    <span class="text-xs mt-2 text-center font-medium step-label">Setrika</span>
                </div>

                {{-- Step 4: Selesai --}}
                <div class="flex flex-col items-center relative z-10 step-item" data-step="4">
                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center bg-white transition-all step-circle">
                        <span class="text-sm">✓</span>
                    </div>
                    <span class="text-xs mt-2 text-center font-medium step-label">Selesai</span>
                </div>
            </div>
        </div>

        {{-- Status Badge --}}
        <div class="text-center mb-4">
            <span id="res-status-badge" class="inline-block px-4 py-2 rounded-full text-sm font-semibold"></span>
        </div>

        {{-- Details --}}
        <div class="border-t pt-4 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Total Biaya</span>
                <span id="res-harga" class="font-medium text-gray-800"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Update Terakhir</span>
                <span id="res-updated" class="font-medium text-gray-800"></span>
            </div>
        </div>
    </div>
</div>

{{-- Shiro Laundry Info & Contact Section --}}
<div class="max-w-2xl mx-auto px-4 py-16">
    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100 text-center">
        <div class="w-16 h-16 bg-blue-50 text-accent rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
            🧺
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Shiro Laundry</h2>
        <p class="text-gray-600 text-sm max-w-md mx-auto mb-6">
            Melayani laundry kiloan dan satuan dengan standar higienis, bersih, wangi tahan lama, dan tepat waktu.
        </p>

        <div class="grid sm:grid-cols-2 gap-4 text-left border-t border-b py-4 my-6 text-sm">
            <div class="flex items-start gap-3">
                <span class="text-xl">📍</span>
                <div>
                    <p class="font-semibold text-gray-800">Alamat</p>
                    <p class="text-gray-500">Jl. Sutomo No. 12, Samarinda, Kalimantan Timur</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-xl">⏰</span>
                <div>
                    <p class="font-semibold text-gray-800">Jam Operasional</p>
                    <p class="text-gray-500">Buka Setiap Hari: 08.00 - 21.00 WITA</p>
                </div>
            </div>
        </div>

        <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-3 rounded-xl transition shadow hover:shadow-md">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492l4.624-1.467A11.956 11.956 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.143 0-4.136-.67-5.774-1.81l-.404-.26-2.735.868.804-2.63-.282-.431A9.96 9.96 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
            Hubungi WhatsApp Shiro Laundry
        </a>
    </div>
</div>

{{-- Footer --}}
<footer class="bg-gray-800 text-gray-300 py-8">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="font-semibold text-white mb-2">Shiro Laundry</p>
        <p class="text-sm">Laundry Bersih, Wangi & Tepat Waktu</p>
        <p class="text-sm mt-4 text-gray-500">&copy; {{ date('Y') }} Shiro Laundry Samarinda. All rights reserved.</p>
        <a href="{{ route('login') }}" class="text-xs text-gray-600 hover:text-gray-400 mt-2 inline-block">Admin & Kasir Panel</a>
    </div>
</footer>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function handleTracking(e) {
        e.preventDefault();

        const nota = document.getElementById('nomor_nota').value.trim();
        const hp = document.getElementById('verifikasi_hp').value.trim();
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const resultArea = document.getElementById('result-area');
        const errorMessage = document.getElementById('error-message');
        const successResult = document.getElementById('success-result');

        if (!nota) return;

        // Show loading
        btnText.textContent = 'Mencari...';
        btnSpinner.classList.remove('hidden');
        resultArea.classList.add('hidden');
        errorMessage.classList.add('hidden');
        successResult.classList.add('hidden');

        try {
            const body = { nomor_nota: nota };
            if (hp.length === 4) body.verifikasi_hp = hp;

            const response = await fetch('{{ route("tracking.search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            });

            const data = await response.json();
            resultArea.classList.remove('hidden');

            if (!data.success) {
                errorMessage.classList.remove('hidden');
                document.getElementById('error-text').textContent = data.message;
                return;
            }

            // Show success result
            successResult.classList.remove('hidden');
            const d = data.data;

            document.getElementById('res-nota').textContent = d.nomor_nota;
            document.getElementById('res-nama').textContent = d.nama_pelanggan;
            document.getElementById('res-harga').textContent = 'Rp ' + d.total_harga;
            document.getElementById('res-updated').textContent = d.updated_at;

            // Status badge
            const badge = document.getElementById('res-status-badge');
            const badgeColors = {
                'antrean': 'bg-yellow-100 text-yellow-800',
                'proses_cuci': 'bg-blue-100 text-blue-800',
                'setrika_packing': 'bg-purple-100 text-purple-800',
                'selesai': 'bg-green-100 text-green-800',
            };
            badge.className = 'inline-block px-4 py-2 rounded-full text-sm font-semibold ' + (badgeColors[d.status_cucian] || 'bg-gray-100 text-gray-800');
            badge.textContent = d.status_label;

            // Progress bar
            updateProgressBar(d.status_step, d.total_steps);

        } catch (err) {
            resultArea.classList.remove('hidden');
            errorMessage.classList.remove('hidden');
            document.getElementById('error-text').textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        } finally {
            btnText.textContent = 'Cari Status Cucian';
            btnSpinner.classList.add('hidden');
        }
    }

    function updateProgressBar(currentStep, totalSteps) {
        const steps = document.querySelectorAll('.step-item');
        const progressLine = document.getElementById('progress-line');

        // Calculate progress line width
        const percentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        progressLine.style.width = Math.min(percentage, 100) + '%';

        steps.forEach((step) => {
            const stepNum = parseInt(step.dataset.step);
            const circle = step.querySelector('.step-circle');
            const label = step.querySelector('.step-label');

            if (stepNum <= currentStep) {
                // Active / completed
                circle.classList.add('bg-accent', 'border-accent', 'text-white');
                circle.classList.remove('bg-white', 'border-gray-300', 'text-gray-400');
                label.classList.add('text-accent');
                label.classList.remove('text-gray-400');
            } else {
                // Pending
                circle.classList.remove('bg-accent', 'border-accent', 'text-white');
                circle.classList.add('bg-white', 'border-gray-300', 'text-gray-400');
                label.classList.remove('text-accent');
                label.classList.add('text-gray-400');
            }
        });
    }
</script>
@endpush
