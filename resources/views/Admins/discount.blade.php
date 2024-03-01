<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel|Data-Discount</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Include the sidebar -->
    @include('Components.sidebar')

    <!-- Create a container for the table to apply styling -->
    <div class="p-4 sm:ml-64">
        <button type="button" class="text-white bg-[#FF9119] hover:bg-[#FF9119]/80 focus:ring-4 focus:outline-none focus:ring-[#FF9119]/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:hover:bg-[#FF9119]/80 dark:focus:ring-[#FF9119]/40 me-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <a href="/admin/add-discount">ADD DATA </a>
        </button>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            NO
                        </th>
                        <th scope="col" class="px-6 py-3">
                            NAMA DISCOUNT
                        </th>
                        <th scope="col" class="px-6 py-3">
                            PERSENTASE DISCOUNT
                        </th>
                        <th scope="col" class="px-6 py-3">
                            DISCOUNT NOMINAL
                        </th>
                        <th scope="col" class="px-6 py-3">
                            TANGGAL AWAL
                        </th>
                        <th scope="col" class="px-6 py-3">
                            TANGGAL AKHIR
                        </th>
                        <th scope="col" class="px-6 py-3">
                            STATUS
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discounts as $key => $discount)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $key + 1 }}</td>
                            <td class="px-6 py-4">{{ $discount->nama_discount }}</td>
                            <td class="px-6 py-4">{{ $discount->discount_amount }}%</td>
                            <td class="px-6 py-4">{{ $discount->start_date }}</td>
                            <td class="px-6 py-4">{{ $discount->end_date }}</td>
                            <td class="px-6 py-4">
                                @if(now()->greaterThan($discount->start_date) && now()->lessThan($discount->end_date))
                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                    <span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span>
                                    Available
                                </span>
                                @elseif(now()->lessThan($discount->start_date))
                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                    <span class="w-2 h-2 me-1 bg-yellow-500 rounded-full"></span>
                                    Coming Soon
                                </span>
                                @else
                                <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                    <span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span>
                                    Unavailable
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <a href="{{ route('discount.detail', $discount->id) }}">DETAIL</a>
                                </button>
                                <form action="{{ route('discount.delete', $discount->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
