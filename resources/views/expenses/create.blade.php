<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Catat Pengeluaran Baru') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('expenses.store') }}" method="POST">
                        @csrf
                        <div class="mt-4">
                            <x-input-label for="expense_category_id" :value="__('Kategori Pengeluaran')" />
                            <select name="expense_category_id" id="expense_category_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('expense_category_id') == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('expense_category_id')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                                :value="old('description')" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="amount" :value="__('Jumlah (Rp)')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount"
                                    :value="old('amount')" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="expense_date" :value="__('Tanggal Pengeluaran')" />
                                <x-text-input id="expense_date" class="block mt-1 w-full" type="date"
                                    name="expense_date" :value="old('expense_date', now()->toDateString())" required />
                                <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('expenses.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
