<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Reservasi Baru
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="guest_id" value="Pilih Tamu" />
                            <select name="guest_id" id="guest_id" class="block mt-1 w-full border-gray-300 rounded-md"
                                required>
                                <option value="">-- Pilih dari data tamu --</option>
                                @foreach ($guests as $guest)
                                    <option value="{{ $guest->id }}" @selected(old('guest_id') == $guest->id)>
                                        {{ $guest->name }} ({{ $guest->id_number }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('guest_id')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">Belum ada data tamu? <a
                                    href="{{ route('guests.create') }}" class="text-indigo-600">Tambah baru</a>.</p>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="check_in_date" value="Tanggal Check-in" />
                                <x-text-input id="check_in_date" type="datetime-local" name="check_in_date"
                                    class="block mt-1 w-full" :value="old('check_in_date')" required />
                            </div>
                            <div>
                                <x-input-label for="check_out_date" value="Tanggal Check-out" />
                                <x-text-input id="check_out_date" type="datetime-local" name="check_out_date"
                                    class="block mt-1 w-full" :value="old('check_out_date')" required />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label value="Pilih Kamar (Bisa lebih dari satu)" />
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($rooms as $room)
                                    <label for="room_{{ $room->id }}"
                                        class="flex items-center p-3 border rounded-md has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-400">
                                        <input type="checkbox" id="room_{{ $room->id }}" name="room_ids[]"
                                            value="{{ $room->id }}" class="h-4 w-4 rounded">
                                        <div class="ml-3 text-sm">
                                            <span class="font-medium">#{{ $room->room_number }}</span>
                                            <p class="text-gray-500">{{ $room->roomType->name }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('room_ids')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>Buat Reservasi</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
