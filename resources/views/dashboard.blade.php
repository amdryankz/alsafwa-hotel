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
                    <div class="flex items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Kalender Hunian</h3>
                        <div class="ml-4 flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <span class="h-3 w-3 rounded-full" style="background-color: #3788d8;"></span>
                                <span class="ml-2">Reservasi</span>
                            </div>
                            <div class="flex items-center">
                                <span class="h-3 w-3 rounded-full" style="background-color: #f59e0b;"></span>
                                <span class="ml-2">Checked-in</span>
                            </div>
                        </div>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Tampilan awal bulan
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    // Tambahkan opsi tampilan minggu dan hari
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '/calendar-events',
                locale: 'id',
                timeZone: 'Asia/Jakarta',
                // Buka URL di tab baru saat event diklik
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // Mencegah browser mengikuti link default
                    if (info.event.url) {
                        window.open(info.event.url, "_blank"); // Buka di tab baru
                    }
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>
