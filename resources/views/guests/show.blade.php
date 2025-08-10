<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Riwayat Tamu: ') }} {{ $guest->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Profil Tamu</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-900 dark:text-gray-100"><strong>Nama Lengkap:</strong>
                                {{ $guest->name }}</p>
                            <p class="text-gray-900 dark:text-gray-100"><strong>Identitas:</strong> {{ $guest->id_type }}
                                - {{ $guest->id_number }}</p>
                        </div>
                        <div>
                            <p class="text-gray-900 dark:text-gray-100"><strong>Nomor Telepon:</strong>
                                {{ $guest->phone_number ?? '-' }}</p>
                            <p class="text-gray-900 dark:text-gray-100"><strong>Alamat:</strong>
                                {{ $guest->address ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('guests.edit', $guest->id) }}"
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-200">Edit
                            Profil</a>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Riwayat Kunjungan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        ID
                                        Booking</th>
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
                                        Kamar
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($guest->bookings as $booking)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('bookings.show', $booking->id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:underline">#{{ $booking->id }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            @foreach ($booking->rooms as $room)
                                                <span
                                                    class="font-semibold">#{{ $room->room_number }}</span>{{ !$loop->last ? ',' : '' }}
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = match ($booking->status) {
                                                    'checked_in'
                                                        => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                                    'checked_out'
                                                        => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                                    'cancelled'
                                                        => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                                    default
                                                        => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ Str::title(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tamu ini
                                            belum
                                            pernah melakukan booking.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
