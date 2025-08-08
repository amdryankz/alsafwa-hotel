@extends('layouts.print')

@section('title', 'Invoice #' . $booking->id)

@section('content')
    <div class="no-print mb-8 text-center">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded shadow-md hover:bg-blue-600">
            Cetak Invoice
        </button>
        <a href="{{ route('bookings.show', $booking->id) }}"
            class="bg-gray-500 text-white px-4 py-2 rounded shadow-md hover:bg-gray-600 ml-2">
            Kembali ke Detail
        </a>
    </div>

    <header class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hotel Hebat</h1>
            <p class="text-gray-500">Jl. Pembangunan No. 123, Medan</p>
            <p class="text-gray-500">telepon: (061) 123-4567</p>
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
            <p>{{ $booking->guest->address ?? 'Alamat tidak tersedia' }}</p>
            <p>{{ $booking->guest->phone_number ?? '' }}</p>
        </div>
        <div class="text-right">
            <p><strong>Tanggal Invoice:</strong> {{ now()->format('d M Y') }}</p>
            <p><strong>Tanggal Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</p>
            <p><strong>Tanggal Check-out:</strong>
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
        </table>
    </section>

    <section class="flex justify-end mb-8">
        <div class="w-full md:w-1/2">
            <table class="w-full">
                <tbody>
                    <tr class="font-semibold">
                        <td class="p-2">Subtotal</td>
                        <td class="p-2 text-right">Rp {{ number_format($booking->total_amount) }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td class="p-2">Sudah Dibayar</td>
                        <td class="p-2 text-right text-green-600">- Rp {{ number_format($booking->paid_amount) }}</td>
                    </tr>
                    <tr class="font-bold text-xl bg-gray-200">
                        <td class="p-3">Sisa Tagihan</td>
                        <td class="p-3 text-right">Rp {{ number_format($booking->total_amount - $booking->paid_amount) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <footer class="text-center text-gray-500 border-t pt-4">
        <p>Terima kasih telah menginap di Hotel Hebat.</p>
        <p>Pembayaran dapat dilakukan melalui transfer ke rekening BCA 123-456-7890 a/n PT Hotel Sejahtera.</p>
    </footer>

@endsection
