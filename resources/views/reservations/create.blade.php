<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            Buat Reservasi Baru
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Error Session Message --}}
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded"
                            role="alert">
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf

                        {{-- Guest Selection --}}
                        <div class="mt-4">
                            <x-input-label for="guest_id" value="Pilih Tamu" class="dark:text-gray-300" />
                            <select name="guest_id" id="select-guest-reservation"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih dari data tamu --</option>
                                @foreach ($guests as $guest)
                                    <option value="{{ $guest->id }}" @selected(old('guest_id') == $guest->id)>
                                        {{ $guest->name }} ({{ $guest->id_number }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('guest_id')" class="mt-2 dark:text-red-400" />
                            <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">Belum ada data tamu? <a
                                    href="{{ route('guests.create') }}"
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Tambah
                                    baru</a>.</p>
                        </div>

                        {{-- Check-in/Check-out Dates --}}
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="check_in_date" value="Tanggal Check-in"
                                    class="dark:text-gray-300" />
                                <x-text-input id="check_in_date" type="datetime-local" name="check_in_date"
                                    class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                    :value="old('check_in_date')" required />
                            </div>
                            <div>
                                <x-input-label for="check_out_date" value="Tanggal Check-out"
                                    class="dark:text-gray-300" />
                                <x-text-input id="check_out_date" type="datetime-local" name="check_out_date"
                                    class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                    :value="old('check_out_date')" required />
                            </div>
                        </div>

                        {{-- Room Selection --}}
                        <div class="mt-4">
                            <x-input-label value="Pilih Kamar (Bisa lebih dari satu)" class="dark:text-gray-300" />
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($rooms as $room)
                                    <label for="room_res_{{ $room->id }}"
                                        class="flex flex-col items-center justify-center p-3 border border-gray-300 dark:border-gray-700 rounded-md cursor-pointer
                                            has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900 has-[:checked]:border-indigo-400 dark:has-[:checked]:border-indigo-600
                                            hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <input type="checkbox" id="room_res_{{ $room->id }}" name="room_ids[]"
                                            value="{{ $room->id }}"
                                            class="h-4 w-4 rounded text-indigo-600 dark:text-indigo-400 border-gray-300 dark:border-gray-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                        <div class="ml-3 text-sm text-center">
                                            <span
                                                class="font-medium text-gray-900 dark:text-gray-100">#{{ $room->room_number }}</span>
                                            <p class="text-gray-500 dark:text-gray-400">{{ $room->roomType->name }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('room_ids')" class="mt-2 dark:text-red-400" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('bookings.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 mr-4">
                                Batal
                            </a>
                            <x-primary-button class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Buat
                                Reservasi</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#select-guest-reservation', {
                create: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });
        });
    </script>
</x-app-layout>
