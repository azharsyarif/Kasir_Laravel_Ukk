<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel | Data-Discount</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Include the sidebar -->
    @include('Components.sidebar')

    <!-- Create a container for the table to apply styling -->
    <!-- Detail Discount -->
<div class="p-4 sm:ml-64">
    <h1 class="flex items-center text-5xl font-bold dark:text-white">EDIT<span class="bg-blue-100 text-blue-800 text-2xl font-semibold me-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-2">DISCOUNT</span></h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <form action="{{ route('admin.discount.update', $discount->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nama_discount" class="block text-sm font-medium text-gray-700">Nama Discount</label>
                <input type="text" name="nama_discount" id="nama_discount" value="{{ $discount->nama_discount }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="discount_amount" class="block text-sm font-medium text-gray-700">Persentase Discount</label>
                <input type="text" name="discount_amount" id="discount_amount" value="{{ $discount->discount_amount }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $discount->start_date }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ $discount->end_date }}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</div>

</body>
</html>
