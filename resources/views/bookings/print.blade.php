@extends('layouts.print')

@section('title', 'Invoice #' . str_pad($booking->id, 6, '0', STR_PAD_LEFT))

@section('content')
    <div class="no-print mb-8 text-center">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600">
            Cetak Invoice
        </button>
    </div>

    <header class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hotel Hebat</h1>
            <p class="text-gray-500">Jl. Pembangunan No. 123, Medan</p>
        </div>
        <div class="text-right">
            <h2 class="text-2xl font-bold text-gray-700">INVOICE</h2>
            <p class="text-gray-500">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </header>

    <section class="flex justify-between mb-8">
        <div>
            <h3 class="font-bold text-gray-700">Ditagihkan Kepada:</h3>
            <p>{{ $booking->guest->name }}</p>
        </div>
        <div class="text-right">
            <p><strong>Tgl Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</p>
            <p><strong>Tgl Check-out:</strong>
                {{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') : 'N/A' }}
            </p>
        </div>
    </section>

    <section class="mb-8">
        <table class="w-full text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->rooms as $room)
                    <tr class="border-b">
                        <td class="p-3">
                            Sewa Kamar #{{ $room->room_number }} ({{ $room->roomType->name }})
                            <span class="block text-xs text-gray-500">{{ $nights }} Malam x Rp
                                {{ number_format($room->pivot->price_at_booking) }}</span>
                        </td>
                        <td class="p-3 text-right">Rp {{ number_format($room->pivot->price_at_booking * $nights) }}</td>
                    </tr>
                @endforeach
                @foreach ($booking->services as $service)
                    <tr class="border-b">
                        <td class="p-3">{{ $service->service_name }} x {{ $service->quantity }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($service->price * $service->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section class="flex justify-end mb-8">
        <div class="w-full md:w-2/3">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="p-2">Subtotal</td>
                        <td class="p-2 text-right">Rp {{ number_format($booking->total_amount) }}</td>
                    </tr>
                    @if ($booking->discount > 0)
                        <tr>
                            <td class="p-2">Diskon</td>
                            <td class="p-2 text-right">- Rp {{ number_format($booking->discount) }}</td>
                        </tr>
                    @endif
                    @if ($booking->tax_percentage > 0)
                        <tr>
                            <td class="p-2">PPN ({{ $booking->tax_percentage }}%)</td>
                            <td class="p-2 text-right">+ Rp
                                {{ number_format(($booking->total_amount - $booking->discount) * ($booking->tax_percentage / 100)) }}
                            </td>
                        </tr>
                    @endif
                    <tr class="font-bold text-lg border-t-2 border-gray-300">
                        <td class="p-2">Grand Total</td>
                        <td class="p-2 text-right">Rp {{ number_format($booking->grand_total) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <h3 class="font-bold text-gray-700 mb-2">Rincian Pembayaran</h3>
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2">Tanggal</th>
                    <th class="p-2">Metode</th>
                    <th class="p-2 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($booking->payments as $payment)
                    <tr class="border-b">
                        <td class="p-2">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                        <td class="p-2">{{ Str::title($payment->payment_method) }}</td>
                        <td class="p-2 text-right">Rp {{ number_format($payment->amount) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-2 text-center text-gray-500">Belum ada pembayaran</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="font-bold">
                <tr>
                    <td colspan="2" class="p-2 border-t-2 border-gray-300">Total Dibayar</td>
                    <td class="p-2 border-t-2 border-gray-300 text-right">Rp {{ number_format($booking->paid_amount) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-2">Sisa Tagihan</td>
                    <td class="p-2 text-right">Rp {{ number_format($booking->grand_total - $booking->paid_amount) }}</td>
                </tr>
            </tfoot>
        </table>
    </section>

    <footer class="text-center text-gray-500 border-t pt-4 mt-8">
        <p>Terima kasih telah menginap di Hotel Hebat.</p>
    </footer>
@endsection
