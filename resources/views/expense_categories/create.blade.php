<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Tambah Kategori Pengeluaran Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('expense-categories.store') }}" method="POST">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Kategori Pengeluaran')" class="dark:text-gray-300" />
                            <x-text-input id="name"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('expense-categories.index') }}"
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
