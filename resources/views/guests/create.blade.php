<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Tambah Data Tamu Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('guests.store') }}" method="POST">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-300" />
                            <x-text-input id="name"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="id_type" :value="__('Tipe Identitas')" class="dark:text-gray-300" />
                                <select name="id_type" id="id_type"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                    <option value="KTP" @selected(old('id_type') == 'KTP')>KTP</option>
                                    <option value="Paspor" @selected(old('id_type') == 'Paspor')>Paspor</option>
                                </select>
                                <x-input-error :messages="$errors->get('id_type')" class="mt-2 dark:text-red-400" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="id_number" :value="__('Nomor Identitas')" class="dark:text-gray-300" />
                                <x-text-input id="id_number"
                                    class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                    type="text" name="id_number" :value="old('id_number')" required />
                                <x-input-error :messages="$errors->get('id_number')" class="mt-2 dark:text-red-400" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Nomor Telepon (Opsional)')" class="dark:text-gray-300" />
                            <x-text-input id="phone_number"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="phone_number" :value="old('phone_number')" />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat (Opsional)')" class="dark:text-gray-300" />
                            <textarea name="address" id="address" rows="3"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('guests.index') }}"
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
