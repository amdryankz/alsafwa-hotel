<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Transaksi #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-lg font-medium text-gray-900">Rincian Tagihan</h3>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status == 'checked_in' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ Str::title(str_replace('_', ' ', $booking->status)) }}</span>
                        </div>
                        <div class="mt-4 border-t border-gray-200">
                            <dl class="divide-y divide-gray-200">
                                @foreach ($booking->rooms as $room)
                                    <div class="py-3 flex justify-between text-sm">
                                        <dt class="text-gray-500">
                                            Sewa Kamar #{{ $room->room_number }} ({{ $room->roomType->name }})<br>
                                            <span class="text-xs">{{ $nights }} Malam x Rp
                                                {{ number_format($room->pivot->price_at_booking) }}</span>
                                        </dt>
                                        <dd class="text-gray-900">Rp
                                            {{ number_format($room->pivot->price_at_booking * $nights) }}</dd>
                                    </div>
                                @endforeach
                                @foreach ($booking->services as $service)
                                    <div class="py-3 flex justify-between text-sm">
                                        <dt class="text-gray-500">{{ $service->service_name }}
                                            (x{{ $service->quantity }})</dt>
                                        <dd class="text-gray-900">Rp
                                            {{ number_format($service->price * $service->quantity) }}</dd>
                                    </div>
                                @endforeach

                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-600">Subtotal</dt>
                                    <dd class="text-gray-900">Rp {{ number_format($booking->total_amount) }}</dd>
                                </div>
                                @if ($booking->discount > 0)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-green-600">Diskon</dt>
                                        <dd class="text-green-600">- Rp {{ number_format($booking->discount) }}</dd>
                                    </div>
                                @endif
                                @if ($booking->tax_percentage > 0)
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-600">PPN ({{ $booking->tax_percentage }}%)</dt>
                                        <dd class="text-gray-900">+ Rp
                                            {{ number_format(($booking->total_amount - $booking->discount) * ($booking->tax_percentage / 100)) }}
                                        </dd>
                                    </div>
                                @endif
                                <div class="py-3 flex justify-between text-base font-bold">
                                    <dt>Grand Total</dt>
                                    <dd>Rp {{ number_format($booking->grand_total) }}</dd>
                                </div>
                                <div class="py-3 flex justify-between text-sm font-medium">
                                    <dt class="text-gray-600">Total Dibayar</dt>
                                    <dd>Rp {{ number_format($booking->paid_amount) }}</dd>
                                </div>
                                <div
                                    class="py-3 flex justify-between text-base font-bold {{ $booking->grand_total - $booking->paid_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    <dt>Sisa Tagihan</dt>
                                    <dd>Rp {{ number_format($booking->grand_total - $booking->paid_amount) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pembayaran</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="p-2 text-left">Tanggal Bayar</th>
                                        <th class="p-2 text-left">Metode</th>
                                        <th class="p-2 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($booking->payments as $payment)
                                        <tr class="border-b">
                                            <td class="p-2">
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, H:i') }}
                                            </td>
                                            <td class="p-2">{{ Str::title($payment->payment_method) }}</td>
                                            <td class="p-2 text-right">Rp {{ number_format($payment->amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-2 text-center text-gray-500">Belum ada
                                                pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Diskon & PPN</h3>
                        <form action="{{ route('bookings.adjustments.store', $booking) }}" method="POST">
                            @csrf
                            <div>
                                <x-input-label for="discount">Diskon (Nominal Rp)</x-input-label>
                                <x-text-input name="discount" id="discount" type="number" class="w-full mt-1"
                                    :value="old('discount', $booking->discount)" />
                            </div>
                            <div class="mt-4">
                                <x-input-label for="tax_percentage">PPN (%)</x-input-label>
                                <x-text-input name="tax_percentage" id="tax_percentage" type="number" step="0.01"
                                    class="w-full mt-1" :value="old('tax_percentage', $booking->tax_percentage)" />
                            </div>
                            <x-primary-button class="mt-4 w-full justify-center">Update</x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Layanan</h3>
                        <form action="{{ route('bookings.services.store', $booking) }}" method="POST">
                            @csrf
                            <x-text-input name="service_name" placeholder="Nama Layanan" class="w-full" required />
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <x-text-input name="price" type="number" placeholder="Harga" class="w-full"
                                    required />
                                <x-text-input name="quantity" type="number" placeholder="Jml" value="1"
                                    class="w-full" required />
                            </div>
                            <x-primary-button class="mt-4 w-full justify-center">Tambah</x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Catat Pembayaran</h3>
                        <form action="{{ route('bookings.payments.store', $booking) }}" method="POST">
                            @csrf
                            <x-text-input name="amount" type="number" placeholder="Jumlah Bayar" class="w-full"
                                required :value="max(0, $booking->grand_total - $booking->paid_amount)" />
                            <div class="mt-2">
                                <x-input-label for="payment_date">Tanggal Bayar</x-input-label>
                                <x-text-input id="payment_date" name="payment_date" type="datetime-local" class="w-full"
                                    required :value="now()->format('Y-m-d\TH:i')" />
                            </div>
                            <select name="payment_method"
                                class="mt-2 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="cash">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                                <option value="card">Kartu Kredit/Debit</option>
                            </select>
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            <x-primary-button class="mt-4 w-full justify-center">Bayar</x-primary-button>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Lainnya</h3>
                        <div class="space-y-4">
                            <a href="{{ route('bookings.print', $booking->id) }}" target="_blank"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                                Cetak Invoice
                            </a>
                            <form action="{{ route('bookings.checkout', $booking) }}" method="POST"
                                onsubmit="return confirm('Anda yakin ingin melakukan check-out untuk tamu ini?')">
                                @csrf
                                <button type="submit"
                                    class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                    Proses Check-out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
