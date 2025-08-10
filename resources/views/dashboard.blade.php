<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Card Pendapatan Hari Ini --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pendapatan Hari Ini</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">Rp
                        {{ number_format($revenueToday) }}</p>
                </div>
                {{-- Card Check-in Hari Ini --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Check-in Hari Ini</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $checkInsToday }} Tamu</p>
                </div>
                {{-- Card Kamar Terisi --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Kamar Terisi</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $occupiedRooms }} /
                        {{ $totalRooms }}</p>
                </div>
                {{-- Card Tingkat Hunian --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 transform hover:scale-105 transition-transform duration-200">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tingkat Hunian</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($occupancyRate, 1) }}%</p>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-200">
                    <div class="flex items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Kalender Pengunjung</h3>
                        <div class="ml-4 flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <span class="h-3 w-3 rounded-full" style="background-color: #3788d8;"></span>
                                <span class="ml-2 dark:text-gray-300">Reservasi</span>
                            </div>
                            <div class="flex items-center">
                                <span class="h-3 w-3 rounded-full" style="background-color: #f59e0b;"></span>
                                <span class="ml-2 dark:text-gray-300">Check-in</span>
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
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '/calendar-events',
                locale: 'id',
                timeZone: 'Asia/Jakarta',
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.open(info.event.url, "_blank");
                    }
                },
            });
            calendar.render();
        });
    </script>
</x-app-layout>
