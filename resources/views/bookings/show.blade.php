<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Transaksi #{{ $booking->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Tamu</h3>
                        <p><strong>Nama:</strong> {{ $booking->guest->name }}</p>
                        <p><strong>Check-in:</strong>
                            {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y, H:i') }}</p>
                        <p><strong>Status:</strong> <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status == 'checked_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ Str::title(str_replace('_', ' ', $booking->status)) }}</span>
                        </p>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 border-t pt-4">Rincian Tagihan</h3>
                    <table class="min-w-full divide-y divide-gray-200 mt-2">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $checkInDate = \Carbon\Carbon::parse($booking->check_in_date)->startOfDay();
                                $checkOutDate = \Carbon\Carbon::parse($booking->check_out_date)->startOfDay();

                                $nights = $checkInDate->diffInDays($checkOutDate);

                                if ($nights <= 0) {
                                    $nights = 1;
                                }
                            @endphp

                            @foreach ($booking->rooms as $room)
                                <td class="px-2 py-2">
                                    <div>Sewa Kamar #{{ $room->room_number }} ({{ $room->roomType->name }})</div>
                                    <div class="text-xs text-gray-500">{{ $nights }} Malam x Rp
                                        {{ number_format($room->pivot->price_at_booking) }}</div>
                                </td>
                                <td class="px-2 py-2 text-right">Rp
                                    {{ number_format($room->pivot->price_at_booking * $nights) }}</td>
                            @endforeach
                            @foreach ($booking->services as $service)
                                <tr>
                                    <td class="px-2 py-2">{{ $service->service_name }} (x{{ $service->quantity }})
                                    </td>
                                    <td class="px-2 py-2 text-right">Rp
                                        {{ number_format($service->price * $service->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-2 py-2 font-bold">Total Tagihan</td>
                                <td class="px-2 py-2 text-right font-bold">Rp
                                    {{ number_format($booking->total_amount) }}</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-2">Sudah Dibayar</td>
                                <td class="px-2 py-2 text-right">Rp {{ number_format($booking->paid_amount) }}</td>
                            </tr>
                            <tr class="text-lg">
                                <td class="px-2 py-2 font-extrabold">Sisa Tagihan</td>
                                <td class="px-2 py-2 text-right font-extrabold">Rp
                                    {{ number_format($booking->total_amount - $booking->paid_amount) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Layanan</h3>
                    <form action="{{ route('bookings.services.store', $booking) }}" method="POST">
                        @csrf
                        <x-text-input name="service_name" placeholder="Nama Layanan" class="w-full" required />
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <x-text-input name="price" type="number" placeholder="Harga" class="w-full" required />
                            <x-text-input name="quantity" type="number" placeholder="Jml" value="1" class="w-full"
                                required />
                        </div>
                        <x-primary-button class="mt-4 w-full justify-center">Tambah</x-primary-button>
                    </form>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Catat Pembayaran</h3>
                    <form action="{{ route('bookings.payments.store', $booking) }}" method="POST">
                        @csrf
                        <x-text-input name="amount" type="number" placeholder="Jumlah Bayar" class="w-full" required
                            :value="$booking->total_amount - $booking->paid_amount" />
                        <select name="payment_method" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm"
                            required>
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
                            class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700">
                            Cetak Invoice
                        </a>
                        <form action="{{ route('bookings.checkout', $booking) }}" method="POST"
                            onsubmit="return confirm('Anda yakin ingin melakukan check-out untuk tamu ini?')">
                            @csrf
                            <button type="submit"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700">
                                Proses Check-out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
