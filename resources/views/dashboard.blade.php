<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 truncate">Pendapatan Hari Ini</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">Rp {{ number_format($revenueToday) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 truncate">Check-in Hari Ini</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $checkInsToday }} Tamu</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 truncate">Kamar Terisi</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $occupiedRooms }} / {{ $totalRooms }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500 truncate">Tingkat Hunian</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($occupancyRate, 1) }}%</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Grafik Pendapatan (7 Hari Terakhir)</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($revenueChart['labels']),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($revenueChart['data']),
                    fill: true,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
