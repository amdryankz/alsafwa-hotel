<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $pageTitle }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-4 -mb-px">
                            <a href="{{ route('bookings.index', ['status' => 'active']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                    {{ $currentStatus == 'active' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                Transaksi Aktif
                            </a>
                            <a href="{{ route('bookings.index', ['status' => 'history']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                    {{ $currentStatus == 'history' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                Riwayat Transaksi
                            </a>
                        </div>
                    </div>

                    @if ($currentStatus == 'history')
                        <div
                            class="my-4 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md">
                            <form method="GET" action="{{ route('bookings.index') }}"
                                class="flex flex-wrap items-end gap-4">
                                <input type="hidden" name="status" value="history">
                                <div>
                                    <label for="start_date"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari
                                        Tanggal</label>
                                    <x-text-input type="date" name="start_date" id="start_date"
                                        class="dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                        :value="$startDate ?? ''" />
                                </div>
                                <div>
                                    <label for="end_date"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sampai
                                        Tanggal</label>
                                    <x-text-input type="date" name="end_date" id="end_date"
                                        class="dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                        :value="$endDate ?? ''" />
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-primary-button
                                        class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Filter</x-primary-button>
                                    <a href="{{ route('bookings.index', ['status' => 'history']) }}"
                                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-500">Reset</a>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($currentStatus == 'active')
                        <div class="flex flex-wrap gap-2 mb-4">
                            <a href="{{ route('reservations.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-700 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800">
                                + Reservasi Baru
                            </a>
                            <a href="{{ route('bookings.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-600 dark:focus:ring-offset-gray-800">
                                + Check-in Langsung
                            </a>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-800 border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded relative my-4"
                            role="alert">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                @if ($currentStatus == 'active')
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Nama
                                            Tamu</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Tgl
                                            Check-in</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Total Tagihan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Sisa
                                            Tagihan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Status Booking</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Pembayaran</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                @else
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Nama
                                            Tamu</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Tgl
                                            Check-in</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Tgl
                                            Check-out</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                            Total Tagihan</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($bookings as $booking)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        @if ($currentStatus == 'active')
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                {{ $booking->guest->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y, H:i') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-gray-100">
                                                Rp
                                                {{ number_format($booking->grand_total) }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap font-semibold text-red-600 dark:text-red-400">
                                                Rp
                                                {{ number_format($booking->grand_total - $booking->paid_amount) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = match ($booking->status) {
                                                        'booked'
                                                            => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                                        'checked_in'
                                                            => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                                        default
                                                            => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
                                                    };
                                                @endphp
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ Str::title(str_replace('_', ' ', $booking->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($booking->grand_total - $booking->paid_amount <= 0.01)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        Lunas
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                                        Belum Lunas
                                                    </span>
                                                @endif
                                            </td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                {{ $booking->guest->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">Rp
                                                {{ number_format($booking->grand_total) }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('bookings.show', $booking->id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada data untuk ditampilkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $bookings->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
