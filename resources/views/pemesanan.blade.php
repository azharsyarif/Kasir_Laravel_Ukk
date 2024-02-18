<!-- resources/views/order/create.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    @include('Components.navbar')
    <div class="p-4 sm:ml-64">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            NO
                        </th>
                        <th scope="col" class="px-6 py-3">
                            USER_ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            TRANSACTION_DATE
                        </th>
                        <th scope="col" class="px-6 py-3">
                            DISCOUNT NOMINAL
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $key =>$transaction)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ strval($key + 1) }}</td>
                            <td class="px-6 py-4">{{ $transaction->user_id}}</td>
                            <td class="px-6 py-4">{{ $transaction->transaction_date }}</td>
                            <td class="px-6 py-4">
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <a href="{{ route('transaction.detail', $transaction->id) }}">DETAIL</a>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>
</body>
</html>
