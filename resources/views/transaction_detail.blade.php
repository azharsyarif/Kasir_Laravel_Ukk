<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel | Transaction Detail</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Include the sidebar -->
    @include('Components.navbar')
    <!-- Create a container for the table to apply styling -->
    <!-- Detail Discount -->

<div class="p-4 sm:ml-64">
    <h1>TRANSACTION DETAIL</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        NO
                    </th>
                    <th scope="col" class="px-6 py-3">
                        PRODUCT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        QUANTITY
                    </th>
                    <th scope="col" class="px-6 py-3">
                        HARGA TOTAL
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactionDetails as $key =>$transactionDetail)
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        <td class="px-6 py-4">{{ strval($key + 1) }}</td>
        <td class="px-6 py-4">{{ $transactionDetail->product->nama_product }}</td>
        <td class="px-6 py-4">{{ $transactionDetail->qty}}</td>
        <td class="px-6 py-4">{{ $transactionDetail->harga_total}}</td>
    </tr>
@endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
