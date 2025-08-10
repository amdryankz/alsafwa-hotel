<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Edit Tipe Kamar: ') . $roomType->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('room-types.update', $roomType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Nama Tipe Kamar')" class="dark:text-gray-300" />
                            <x-text-input id="name"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="name" :value="old('name', $roomType->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price_per_night" :value="__('Harga per Malam (Rp)')" class="dark:text-gray-300" />
                            <x-text-input id="price_per_night"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="number" name="price_per_night" :value="old('price_per_night', $roomType->price_per_night)" required />
                            <x-input-error :messages="$errors->get('price_per_night')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" class="dark:text-gray-300" />
                            <textarea name="description" id="description" rows="4"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $roomType->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('room-types.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4 transition-colors duration-200">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
