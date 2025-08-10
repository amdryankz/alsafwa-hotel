<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $pageTitle }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6 border-b border-gray-200">
                        <div class="flex space-x-4 -mb-px">
                            <a href="{{ route('bookings.index', ['status' => 'active']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                      {{ $currentStatus == 'active' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Transaksi Aktif
                            </a>
                            <a href="{{ route('bookings.index', ['status' => 'history']) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                      {{ $currentStatus == 'history' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Riwayat Transaksi
                            </a>
                        </div>
                    </div>

                    @if ($currentStatus == 'history')
                        <div class="my-4 p-4 bg-gray-50 border rounded-md">
                            <form method="GET" action="{{ route('bookings.index') }}"
                                class="flex flex-wrap items-end gap-4">
                                <input type="hidden" name="status" value="history">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Dari
                                        Tanggal</label>
                                    <x-text-input type="date" name="start_date" id="start_date" :value="$startDate ?? ''" />
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai
                                        Tanggal</label>
                                    <x-text-input type="date" name="end_date" id="end_date" :value="$endDate ?? ''" />
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-primary-button>Filter</x-primary-button>
                                    <a href="{{ route('bookings.index', ['status' => 'history']) }}"
                                        class="text-sm text-gray-600 hover:text-indigo-600">Reset</a>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($currentStatus == 'active')
                        <a href="{{ route('reservations.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 mb-4">
                            + Reservasi Baru
                        </a>
                        <a href="{{ route('bookings.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 mb-4">
                            + Check-in Langsung
                        </a>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative my-4"
                            role="alert">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                @if ($currentStatus == 'active')
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                            Tamu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl
                                            Check-in</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Total Tagihan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa
                                            Tagihan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status Booking</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Pembayaran</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama
                                            Tamu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl
                                            Check-in</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl
                                            Check-out</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Total Tagihan</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($bookings as $booking)
                                    <tr>
                                        @if ($currentStatus == 'active')
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->guest->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold">Rp
                                                {{ number_format($booking->grand_total) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-red-600">Rp
                                                {{ number_format($booking->grand_total - $booking->paid_amount) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = match ($booking->status) {
                                                        'booked' => 'bg-blue-100 text-blue-800',
                                                        'checked_in' => 'bg-yellow-100 text-yellow-800',
                                                        default => 'bg-gray-100 text-gray-800',
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
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Lunas
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Belum Lunas
                                                    </span>
                                                @endif
                                            </td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->guest->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp
                                                {{ number_format($booking->grand_total) }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('bookings.show', $booking->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data untuk ditampilkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
