<!-- nota_pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    @vite('resources/css/app.css')

    <style>
        /* Add your CSS styles here */
        <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 18px;
            color: #666666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #dddddd;
            text-align: left;
        }
        th {
            background-color: #f9fafb;
            color: #333333;
        }
        .total-row {
            font-weight: bold;
        }
        .total-info {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
    </style>
</head>
<body>
    <h1>Nota Transaksi</h1>
    <p>Kasir: {{ $cashierName }}</p>
    <p>Nomor Transaksi: {{ $transaction ->id }}</p>
    <p>Tanggal Transaksi: {{ $transaction ->transaction_date }}</p>
    
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
                @foreach($transaction ->details as $detail)
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

        @php
            // Calculate total discount
            $totalDiscount = ($totalDiscountPercentage / 100) * $totalPrice;

            // Calculate total price after discount
            $totalPriceAfterDiscount = $totalPrice - $totalDiscount;
        @endphp

        {{-- <h2>Total Harga Setelah Diskon: Rp{{ number_format($totalPriceAfterDiscount, 2) }}</h2> --}}
    <br>
        <h1 class="items-center">TERIMAH KASIH</h1>

    </div>
</body>
</html>