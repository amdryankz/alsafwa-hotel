<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kamar: ') }} {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="room_number" :value="__('Nomor Kamar')" />
                            <x-text-input id="room_number" class="block mt-1 w-full" type="text" name="room_number"
                                :value="old('room_number', $room->room_number)" required autofocus />
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="room_type_id" :value="__('Tipe Kamar')" />
                            <select name="room_type_id" id="room_type_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Tipe Kamar --</option>
                                @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_type_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="available"
                                    {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Tersedia
                                    (Available)</option>
                                <option value="occupied"
                                    {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Terisi (Occupied)
                                </option>
                                <option value="maintenance"
                                    {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Perbaikan
                                    (Maintenance)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('rooms.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
