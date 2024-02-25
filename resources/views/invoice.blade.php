<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Transaksi</title>
    <style>
        /* CSS styling goes here */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-5 mt-10">
        <h1 class="text-3xl font-bold mb-5">Invoice Transaksi</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border border-gray-400 px-4 py-2">No</th>
                        <th class="border border-gray-400 px-4 py-2">Nama Produk</th>
                        <th class="border border-gray-400 px-4 py-2">Harga</th>
                        <th class="border border-gray-400 px-4 py-2">Jumlah</th>
                        <th class="border border-gray-400 px-4 py-2">Total</th>
                        <th class="border border-gray-400 px-4 py-2">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $index => $transaction)
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-400 px-4 py-2">
                            @foreach ($transaction->details as $detail)
                                {{ $detail->product->nama_product }}<br>
                            @endforeach
                        </td>
                        <td class="border border-gray-400 px-4 py-2">@currency($transaction->details->sum('harga_total'))</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $transaction->details->sum('qty') }}</td>
                        <td class="border border-gray-400 px-4 py-2">@currency($transaction->details->sum('harga_total'))</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $transaction->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Add button to generate PDF -->
        <div class="mt-5">
            <a href="{{ route('generate.invoice.pdf') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Generate PDF</a>
        </div>
    </div>
</body>
</html>
