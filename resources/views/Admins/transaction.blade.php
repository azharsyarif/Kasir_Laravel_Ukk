<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel | Data-Genre Dan Platform</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Include the sidebar -->
    @include('Components.sidebar')

    <!-- Create a container for the table to apply styling -->
    <div class="p-4 sm:ml-64">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="container mx-auto px-5 mt-10">
                <h1 class="text-3xl font-bold mb-5">Invoice Transaksi</h1>
                <!-- Add button to generate PDF -->
                <div class="mb-5">
                    <a href="{{ route('generate.invoice.pdf') }}" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Generate PDF</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border border-gray-400 px-4 py-2">No</th>
                                <th class="border border-gray-400 px-4 py-2">Nama Kasir</th>
                                <th class="border border-gray-400 px-4 py-2">Nama Produk</th>
                                <th class="border border-gray-400 px-4 py-2">Harga Satuan</th>
                                <th class="border border-gray-400 px-4 py-2">Jumlah</th>
                                <th class="border border-gray-400 px-4 py-2">Total Setelah Diskon</th>
                                <th class="border border-gray-400 px-4 py-2">Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $index => $transaction)
                                @foreach ($transaction->details as $detail)
                                    <tr>
                                        <td class="border border-gray-400 px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border border-gray-400 px-4 py-2">
                                            {{ $transaction->user->username ?? 'Unknown' }}
                                        </td>
                                        <td class="border border-gray-400 px-4 py-2">
                                            {{ $detail->product->nama_product }}
                                        </td>
                                        <td class="border border-gray-400 px-4 py-2">@currency($detail->harga_satuan)</td>
                                        <td class="border border-gray-400 px-4 py-2">{{ $detail->qty }}</td>
                                        <td class="border border-gray-400 px-4 py-2">@currency($detail->calculateTotalPriceAfterDiscount())</td>
                                        <td class="border border-gray-400 px-4 py-2">{{ $transaction->created_at->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
