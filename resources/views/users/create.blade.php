<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">{{ __('Tambah Karyawan Baru') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nama')" class="dark:text-gray-300" />
                            <x-text-input id="name"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
                            <x-text-input id="email"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Peran (Role)')" class="dark:text-gray-300" />
                            <select name="role" id="role"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md"
                                required>
                                <option value="front_office">Staff Front Office</option>
                                <option value="accountant">Akuntan</option>
                                <option value="owner">Owner</option>
                                <option value="admin">Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" class="dark:text-gray-300" />
                            <x-text-input id="password"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="dark:text-gray-300" />
                            <x-text-input id="password_confirmation"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('users.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 mr-4 transition-colors duration-200">Batal</a>
                            <x-primary-button
                                class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
