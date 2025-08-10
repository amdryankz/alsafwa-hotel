<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            Detail Transaksi #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded relative"
                    role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded relative"
                    role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div x-data="{ modalOpen: false, oldRoomId: null, oldRoomNumber: '' }" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div x-show="modalOpen" x-transition
                    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
                    style="display: none;">
                    <div @click.away="modalOpen = false"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Pindah Kamar dari #<span
                                x-text="oldRoomNumber"></span>
                        </h3>
                        <form :action="'/bookings/' + {{ $booking->id }} + '/change-room'" method="POST">
                            @csrf
                            <input type="hidden" name="old_room_id" :value="oldRoomId">
                            <div>
                                <x-input-label for="new_room_id" class="mb-1 dark:text-gray-300">Pilih Kamar Baru yang
                                    Tersedia:</x-input-label>
                                <select name="new_room_id" id="new_room_id"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                    <option value="">-- Pilih Kamar --</option>
                                    @foreach ($availableRooms as $room)
                                        <option value="{{ $room->id }}">#{{ $room->room_number }}
                                            ({{ $room->roomType->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @if ($availableRooms->isEmpty())
                                    <p class="text-sm text-red-500 mt-2 dark:text-red-400">Tidak ada kamar lain yang
                                        tersedia saat ini.
                                    </p>
                                @endif
                            </div>
                            <div class="mt-6 flex justify-end space-x-4">
                                <button type="button" @click="modalOpen = false"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">Batal</button>
                                <x-primary-button class="dark:bg-indigo-700 dark:hover:bg-indigo-600">Konfirmasi
                                    Pindah</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Rincian Tagihan</h3>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($booking->status == 'booked') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @elseif($booking->status == 'checked_in') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                @else bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @endif">
                                {{ Str::title(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        <div class="mt-4 border-t border-gray-200 dark:border-gray-700">
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($booking->rooms as $room)
                                    <div class="py-3 flex justify-between items-center text-sm">
                                        <dt class="text-gray-500 dark:text-gray-400">
                                            Sewa Kamar #{{ $room->room_number }} ({{ $room->roomType->name }})<br>
                                            <span class="text-xs">{{ $nights }} Malam x Rp
                                                {{ number_format($room->pivot->price_at_booking) }}</span>
                                        </dt>
                                        <div class="flex items-center">
                                            <dd class="text-gray-900 dark:text-white mr-4">Rp
                                                {{ number_format($room->pivot->price_at_booking * $nights) }}</dd>
                                            @if ($booking->status == 'checked_in')
                                                <button
                                                    @click="modalOpen = true; oldRoomId = {{ $room->id }}; oldRoomNumber = '{{ $room->room_number }}'"
                                                    class="no-print text-xs bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-100 px-2 py-1 rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors duration-200">
                                                    Pindah
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($booking->services as $service)
                                    <div class="py-3 flex justify-between text-sm">
                                        <dt class="text-gray-500 dark:text-gray-400">{{ $service->service_name }}
                                            (x{{ $service->quantity }})
                                        </dt>
                                        <dd class="text-gray-900 dark:text-white">Rp
                                            {{ number_format($service->price * $service->quantity) }}</dd>
                                    </div>
                                @endforeach

                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-600 dark:text-gray-300">Subtotal</dt>
                                    <dd class="text-gray-900 dark:text-white">Rp
                                        {{ number_format($booking->total_amount) }}</dd>
                                </div>
                                @if ($booking->discount > 0)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-green-600 dark:text-green-400">Diskon</dt>
                                        <dd class="text-green-600 dark:text-green-400">- Rp
                                            {{ number_format($booking->discount) }}</dd>
                                    </div>
                                @endif
                                @if ($booking->tax_percentage > 0)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-600 dark:text-gray-300">PPN
                                            ({{ $booking->tax_percentage }}%)</dt>
                                        <dd class="text-gray-900 dark:text-white">+ Rp
                                            {{ number_format(($booking->total_amount - $booking->discount) * ($booking->tax_percentage / 100)) }}
                                        </dd>
                                    </div>
                                @endif
                                <div
                                    class="py-3 flex justify-between text-base font-bold text-gray-900 dark:text-white">
                                    <dt>Grand Total</dt>
                                    <dd>Rp {{ number_format($booking->grand_total) }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-600 dark:text-gray-300">Total Dibayar</dt>
                                    <dd class="text-gray-900 dark:text-white">Rp
                                        {{ number_format($booking->paid_amount) }}</dd>
                                </div>
                                <div
                                    class="py-3 flex justify-between text-base font-bold {{ $booking->grand_total - $booking->paid_amount > 0.01 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    <dt>Sisa Tagihan</dt>
                                    <dd>Rp {{ number_format($booking->grand_total - $booking->paid_amount) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Riwayat Pembayaran</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="p-2 text-left text-gray-500 dark:text-gray-300">Tanggal Bayar</th>
                                        <th class="p-2 text-left text-gray-500 dark:text-gray-300">Metode</th>
                                        <th class="p-2 text-right text-gray-500 dark:text-gray-300">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($booking->payments as $payment)
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <td class="p-2 text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, H:i') }}
                                            </td>
                                            <td class="p-2 text-gray-900 dark:text-gray-100">
                                                {{ Str::title($payment->payment_method) }}</td>
                                            <td class="p-2 text-right text-gray-900 dark:text-gray-100">Rp
                                                {{ number_format($payment->amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-2 text-center text-gray-500 dark:text-gray-400">
                                                Belum ada
                                                pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Diskon & PPN</h3>
                        <form action="{{ route('bookings.adjustments.store', $booking) }}" method="POST">
                            @csrf
                            <div>
                                <x-input-label for="discount" class="dark:text-gray-300">Diskon (Nominal
                                    Rp)</x-input-label>
                                <x-text-input name="discount" id="discount" type="number"
                                    class="w-full mt-1 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                    :value="old('discount', $booking->discount)" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="tax_percentage" class="dark:text-gray-300">PPN (%)</x-input-label>
                                <x-text-input name="tax_percentage" id="tax_percentage" type="number" step="0.01"
                                    class="w-full mt-1 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200"
                                    :value="old('tax_percentage', $booking->tax_percentage)" />
                            </div>
                            <x-primary-button
                                class="mt-4 w-full justify-center dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Update</x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tambah Layanan</h3>
                        <form action="{{ route('bookings.services.store', $booking) }}" method="POST">
                            @csrf
                            <x-text-input name="service_name" placeholder="Nama Layanan"
                                class="w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                required />
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <x-text-input name="price" type="number" placeholder="Harga"
                                    class="w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                    required />
                                <x-text-input name="quantity" type="number" placeholder="Jml" value="1"
                                    class="w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                    required />
                            </div>
                            <x-primary-button
                                class="mt-4 w-full justify-center dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Tambah</x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Catat Pembayaran</h3>
                        <form action="{{ route('bookings.payments.store', $booking) }}" method="POST">
                            @csrf
                            <x-text-input name="amount" type="number" placeholder="Jumlah Bayar" step="0.01"
                                class="w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-500"
                                required :value="max(0, $booking->grand_total - $booking->paid_amount)" />
                            <div class="mt-2">
                                <x-input-label for="payment_date" class="dark:text-gray-300">Tanggal
                                    Bayar</x-input-label>
                                <x-text-input id="payment_date" name="payment_date" type="datetime-local"
                                    class="w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200" required
                                    :value="now()->format('Y-m-d\TH:i')" />
                            </div>
                            <select name="payment_method"
                                class="mt-2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="cash">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                                <option value="card">Kartu Kredit/Debit</option>
                            </select>
                            <x-input-error :messages="$errors->get('amount')" class="mt-2 dark:text-red-400" />
                            <x-primary-button
                                class="mt-4 w-full justify-center dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:text-white">Bayar</x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aksi Lainnya</h3>
                        <div class="space-y-4">
                            <a href="{{ route('bookings.print', $booking->id) }}" target="_blank"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 dark:hover:bg-gray-600 transition-colors duration-200">
                                Cetak Invoice
                            </a>
                            @if ($booking->status == 'booked')
                                <form action="{{ route('bookings.confirmCheckIn', $booking->id) }}" method="POST"
                                    onsubmit="return confirm('Konfirmasi kedatangan dan check-in untuk tamu ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full justify-center inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400 dark:hover:bg-green-600 transition-colors duration-200">
                                        Konfirmasi Check-in
                                    </button>
                                </form>
                                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST"
                                    onsubmit="return confirm('Anda yakin ingin MEMBATALKAN reservasi ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full justify-center inline-flex items-center px-4 py-2 bg-orange-500 dark:bg-orange-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-400 dark:hover:bg-orange-600 transition-colors duration-200">
                                        Batalkan Reservasi
                                    </button>
                                </form>
                            @endif
                            @if ($booking->status == 'checked_in')
                                <form action="{{ route('bookings.checkout', $booking) }}" method="POST"
                                    onsubmit="return confirm('Anda yakin ingin melakukan check-out untuk tamu ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 dark:hover:bg-red-600 transition-colors duration-200">
                                        Proses Check-out
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('bookings.index') }}"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
