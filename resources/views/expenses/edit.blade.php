<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Edit Pengeluaran: ') . $expense->description }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Use PUT method for updates --}}

                        <div class="mt-4">
                            <x-input-label for="expense_category_id" :value="__('Kategori Pengeluaran')" class="dark:text-gray-300" />
                            <select name="expense_category_id" id="expense_category_id"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('expense_category_id', $expense->expense_category_id) == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('expense_category_id')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" class="dark:text-gray-300" />
                            <x-text-input id="description"
                                class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                type="text" name="description" :value="old('description', $expense->description)" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2 dark:text-red-400" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="amount" :value="__('Jumlah (Rp)')" class="dark:text-gray-300" />
                                <x-text-input id="amount"
                                    class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                    type="number" name="amount" :value="old('amount', $expense->amount)" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2 dark:text-red-400" />
                            </div>
                            <div>
                                <x-input-label for="expense_date" :value="__('Tanggal Pengeluaran')" class="dark:text-gray-300" />
                                <x-text-input id="expense_date"
                                    class="block mt-1 w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                    type="date" name="expense_date" :value="old(
                                        'expense_date',
                                        \Carbon\Carbon::parse($expense->expense_date)->toDateString(),
                                    )" required />
                                <x-input-error :messages="$errors->get('expense_date')" class="mt-2 dark:text-red-400" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('expenses.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 mr-4 transition-colors duration-200">Batal</a>
                            <x-primary-button
                                class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Perbarui</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
