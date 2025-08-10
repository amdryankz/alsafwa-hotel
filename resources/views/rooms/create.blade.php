<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Tambah Data Kamar Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('rooms.store') }}" method="POST">
                        @csrf

                        <div>
                            <x-input-label for="room_number" :value="__('Nomor Kamar')" class="dark:text-gray-300" />
                            <x-text-input id="room_number"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="room_number" :value="old('room_number')" required autofocus />
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="room_type_id" :value="__('Tipe Kamar')" class="dark:text-gray-300" />
                            <select name="room_type_id" id="room_type_id"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Tipe Kamar --</option>
                                @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('room_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_type_id')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status Awal')" class="dark:text-gray-300" />
                            <select name="status" id="status"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia
                                    (Available)</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                    Perbaikan (Maintenance)</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('rooms.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4 transition-colors duration-200">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
