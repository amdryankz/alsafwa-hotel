<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Laporan Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('reports.financial') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari
                            Tanggal</label>
                        <x-text-input type="date" name="start_date" id="start_date"
                            class="dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200" :value="$startDate" />
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sampai
                            Tanggal</label>
                        <x-text-input type="date" name="end_date" id="end_date"
                            class="dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200" :value="$endDate" />
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-primary-button type="submit"
                            class="dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Filter</x-primary-button>
                        <a href="{{ route('reports.financial.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Export Excel
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg dark:shadow-xl">
                    <p class="text-lg">Total Pemasukan</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($income) }}</p>
                </div>
                <div class="bg-red-500 text-white p-6 rounded-lg shadow-lg dark:shadow-xl">
                    <p class="text-lg">Total Pengeluaran</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($expenses) }}</p>
                </div>
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg dark:shadow-xl">
                    <p class="text-lg">Laba / Rugi</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($profit) }}</p>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Rincian Pengeluaran per Kategori</h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($expenseDetails as $detail)
                        <li
                            class="py-3 flex justify-between text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 rounded-md px-2 -mx-2">
                            <span class="font-medium">{{ $detail->category->name }}</span>
                            <span class="font-mono">Rp {{ number_format($detail->total) }}</span>
                        </li>
                    @empty
                        <li class="py-3 text-center text-gray-500 dark:text-gray-400">Tidak ada data pengeluaran pada
                            periode ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
