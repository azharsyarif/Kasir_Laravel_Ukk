<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    @vite('resources/css/app.css')

    <style>
        /* Add your CSS styles here */
        
    </style>
</head>
<body>
    <!-- Include the navbar or any other common components -->
    @include('Components.navbar')
    
    <h1>Nota Transaksi</h1>
    <p>Kasir: {{ $cashierName }}</p>
    <p>Nomor Transaksi: {{ $lastTransaction->id }}</p>
    <p>Tanggal Transaksi: {{ $lastTransaction->transaction_date }}</p>
    
    <h2>Detail Produk:</h2>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                    NO
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama Produk
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Quantity
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Harga Satuan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Discount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Subtotal
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPrice = 0;
                @endphp
                @foreach($lastTransaction->details as $detail)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ strval($loop->index + 1) }}</td>
                        <td class="px-6 py-4">{{ $detail->product->nama_product }}</td>
                        <td class="px-6 py-4">{{ $detail->qty }}</td>
                        <td class="px-6 py-4">Rp. {{ number_format($detail->product->harga) }}</td>
                        <td class="px-6 py-4">
                            @php
                                $discountPercentage = 0;
                                // Iterate through genres to find maximum discount
                                if ($detail->product->genres) {
                                    foreach ($detail->product->genres as $genre) {
                                        if ($genre->discountDetails) {
                                            foreach ($genre->discountDetails as $discountDetail) {
                                                // Calculate maximum discount for each genre
                                                $maxDiscount = $discountDetail->discount->discount_amount;
                                                if ($maxDiscount > $discountPercentage) {
                                                    $discountPercentage = $maxDiscount;
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp
                            @if ($discountPercentage > 0)
                                {{ $discountPercentage }}% off
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                // Calculate total price before discount
                                $subtotalBeforeDiscount = $detail->qty * $detail->product->harga;
                                
                                // Calculate total price after discount
                                $subtotalAfterDiscount = $subtotalBeforeDiscount * (1 - $discountPercentage / 100);

                                // Add subtotal after discount to the total price
                                $totalPrice += $subtotalAfterDiscount;
                            @endphp
                            Rp{{ number_format($subtotalAfterDiscount, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>    

        <!-- Display the total price -->
        {{-- <h2>Persentase Diskon Total Transaksi: {{ $totalDiscountPercentage }}%</h2> --}}
        <h2>Total Harga Keseluruhan: Rp{{ number_format($totalPrice, 2) }}</h2>

        {{-- @php
            // Calculate total discount
            $totalDiscount = ($totalDiscountPercentage / 100) * $totalPrice;

            // Calculate total price after discount
            $totalPriceAfterDiscount = $totalPrice - $totalDiscount;
        @endphp

        <h2>Total Harga Setelah Diskon: Rp{{ number_format($totalPriceAfterDiscount, 2) }}</h2> --}}
        
        <button type="button" class="transition-all ease-in duration-75 text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
            <a target="blank" href="{{ route('nota.pdf') }}">Print PDF</a>
        </button>
        
    </div>
</body>
</html>
